-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Hoszt: 127.0.0.1
-- Létrehozás ideje: 2014. Máj 24. 22:09
-- Szerver verzió: 5.5.32
-- PHP verzió: 5.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Adatbázis: `plainhouse`
--
CREATE DATABASE IF NOT EXISTS `plainhouse` DEFAULT CHARACTER SET utf8 COLLATE utf8_hungarian_ci;
USE `plainhouse`;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `admin`
--

CREATE TABLE IF NOT EXISTS `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(75) COLLATE utf8_hungarian_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_hungarian_ci NOT NULL,
  `password` varchar(512) COLLATE utf8_hungarian_ci NOT NULL,
  `role` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=3 ;

--
-- A tábla adatainak kiíratása `admin`
--

INSERT INTO `admin` (`id`, `username`, `email`, `password`, `role`) VALUES
(1, 'admin', '', '05db4ee4c4ebcb0c19571283be40bdceccb73cd83898519a6ed49c5754fafae46a87e2a14c98aba94f1668633471e149970a7544f93f36ef6bd7eb04f5539517', 0),
(2, 'szudvari', 'udvarisz@yahoo.com', '5420720b710f7f14d3d6d12c37c23a32032d14e08d6a13f613c775d28c388a31b3245d5458fda2d869153be6c0872118ef078df042f776210db7fdab8c46a5d3', 0);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `deposits`
--

CREATE TABLE IF NOT EXISTS `deposits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `floor` int(11) NOT NULL,
  `door` int(11) NOT NULL,
  `area` double NOT NULL,
  `residents_no` int(11) NOT NULL,
  `note` text COLLATE utf8_hungarian_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=4 ;

--
-- A tábla adatainak kiíratása `deposits`
--

INSERT INTO `deposits` (`id`, `floor`, `door`, `area`, `residents_no`, `note`) VALUES
(1, 0, 0, 50, 2, 'Kis lakás'),
(2, 0, 1, 55, 1, 'Nagyobb lakás'),
(3, 0, 0, 0, 0, '');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `residents`
--

CREATE TABLE IF NOT EXISTS `residents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(100) COLLATE utf8_hungarian_ci NOT NULL,
  `lastname` varchar(100) COLLATE utf8_hungarian_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_hungarian_ci NOT NULL,
  `username` varchar(25) COLLATE utf8_hungarian_ci NOT NULL,
  `password` varchar(512) COLLATE utf8_hungarian_ci NOT NULL,
  `depositid` int(11) NOT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  `admin` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `deposit_id` (`depositid`),
  UNIQUE KEY `e-mail` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=2 ;

--
-- A tábla adatainak kiíratása `residents`
--

INSERT INTO `residents` (`id`, `firstname`, `lastname`, `email`, `username`, `password`, `depositid`, `active`, `admin`) VALUES
(1, 'Udvari', 'Szabolcs', 'szudvari@gmail.com', 'szudvari', '5420720b710f7f14d3d6d12c37c23a32032d14e08d6a13f613c775d28c388a31b3245d5458fda2d869153be6c0872118ef078df042f776210db7fdab8c46a5d3', 2, 1, 0);

--
-- Megkötések a kiírt táblákhoz
--

--
-- Megkötések a táblához `residents`
--
ALTER TABLE `residents`
  ADD CONSTRAINT `residents_ibfk_1` FOREIGN KEY (`depositid`) REFERENCES `deposits` (`id`) ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
