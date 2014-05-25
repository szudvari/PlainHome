-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Hoszt: 127.0.0.1
-- Létrehozás ideje: 2014. Máj 26. 00:03
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
  `role` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=3 ;

--
-- A tábla adatainak kiíratása `admin`
--

INSERT INTO `admin` (`id`, `username`, `email`, `password`, `role`) VALUES
(1, 'admin', '', '05db4ee4c4ebcb0c19571283be40bdceccb73cd83898519a6ed49c5754fafae46a87e2a14c98aba94f1668633471e149970a7544f93f36ef6bd7eb04f5539517', 1),
(2, 'szudvari', 'udvarisz@yahoo.com', '5420720b710f7f14d3d6d12c37c23a32032d14e08d6a13f613c775d28c388a31b3245d5458fda2d869153be6c0872118ef078df042f776210db7fdab8c46a5d3', 1);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `deposits`
--

CREATE TABLE IF NOT EXISTS `deposits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `floor` varchar(5) COLLATE utf8_hungarian_ci NOT NULL,
  `door` varchar(4) COLLATE utf8_hungarian_ci NOT NULL,
  `area` double NOT NULL,
  `garage_area` double NOT NULL DEFAULT '0',
  `residents_no` int(11) NOT NULL,
  `area_ratio` double NOT NULL,
  `garage_area_ratio` double NOT NULL DEFAULT '0',
  `watermeter` tinyint(1) NOT NULL,
  `resident_name` text COLLATE utf8_hungarian_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=14 ;

--
-- A tábla adatainak kiíratása `deposits`
--

INSERT INTO `deposits` (`id`, `floor`, `door`, `area`, `garage_area`, `residents_no`, `area_ratio`, `garage_area_ratio`, `watermeter`, `resident_name`) VALUES
(1, '1', '1', 91, 18, 3, 3.39, 0.66, 1, 'Döbör István'),
(2, '1', '2', 85, 20, 4, 3.16, 0.74, 1, 'Dr Berka István\r\n'),
(3, '1', '2/A', 72, 0, 3, 2.68, 0, 1, 'Stribik Ágnes\r\n'),
(4, '1', '3', 72, 0, 3, 2.68, 0, 1, 'Papp Győző'),
(5, '1', '4', 85, 0, 2, 3.16, 0, 0, 'BM (Hatvani Zsigmond)'),
(6, '1', '5', 91, 18, 1, 3.39, 0.66, 1, 'Dr Tóth Lászlóné'),
(7, '2', '6', 91, 0, 2, 3.39, 0, 1, 'Dr. Rácz Károly'),
(8, '2', '7', 85, 0, 2, 3.16, 0, 1, 'Sventek Zoltán'),
(9, '2', '8', 72, 0, 2, 2.68, 0, 1, 'Maczuca Mihály'),
(10, '2', '9', 72, 0, 2, 2.68, 0, 1, 'Amferné Pálinkás Mária'),
(11, '2', '10', 85, 18, 2, 3.16, 0.66, 1, 'Kubiszyn Anna'),
(12, '2', '11', 91, 0, 3, 3.39, 0, 1, 'Szabó Csaba'),
(13, '3', '12', 91, 20, 4, 3.39, 0.74, 1, 'Papp Károly');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `fees`
--

CREATE TABLE IF NOT EXISTS `fees` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) COLLATE utf8_hungarian_ci NOT NULL,
  `yearly_amount` int(11) NOT NULL,
  `dealer` int(11) NOT NULL,
  `multiplier` varchar(75) COLLATE utf8_hungarian_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=9 ;

--
-- A tábla adatainak kiíratása `fees`
--

INSERT INTO `fees` (`id`, `name`, `yearly_amount`, `dealer`, `multiplier`) VALUES
(1, 'Víz (vízóra nélkül)\r\n', 160000, 4, '/fő'),
(2, 'Technikai víz', 22000, 13, '/albetét'),
(3, 'Szemétszállítás\r\n', 784707, 100, '/tulajdoni hányad'),
(4, 'Elektromos energia\r\n', 750000, 100, '/tulajdoni hányad'),
(5, 'Gáz felh., kazán', 8468800, 1177, '/terület'),
(6, 'Kamera, riasztó', 73152, 100, '/tulajdon hányad'),
(7, 'Ügyviteli költség', 6000, 100, '/tulajdon hányad'),
(8, 'Lift', 430000, 100, '/tulajdoni hányad');

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
