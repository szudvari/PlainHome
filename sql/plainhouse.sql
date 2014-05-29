-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Hoszt: 127.0.0.1
-- Létrehozás ideje: 2014. Máj 29. 23:23
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
  `residents_no` int(11) NOT NULL,
  `area_ratio` double NOT NULL,
  `resident_name` text COLLATE utf8_hungarian_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=65 ;

--
-- A tábla adatainak kiíratása `deposits`
--

INSERT INTO `deposits` (`id`, `floor`, `door`, `area`, `residents_no`, `area_ratio`, `resident_name`) VALUES
(1, 'fsz.', '1', 52.35, 2, 164, 'JÁVORSZKYNÉ HORVÁTH ILDIKÓ'),
(2, 'fsz.', '2', 57.56, 1, 180, 'SZAHULCSIK JÓZSEF'),
(3, 'fsz.', '3', 57.3, 2, 178, 'DUDÁS JÓZSEFNÉ'),
(4, 'fsz', '4', 57.3, 2, 178, 'RAMASZ TIBOR & TIBORNÉ'),
(5, '1', '5', 57.3, 2, 178, 'Dr. BÚZÁNÉ TÓTH MÁRIA'),
(6, '1', '6', 35, 1, 109, 'TÓTH IZABELLA'),
(7, '1', '7', 57.3, 2, 178, 'BARTHA LEVENTE'),
(8, '1', '8', 56.88, 2, 177, 'SZAUTHER HILDA Dr.'),
(9, '1', '9', 34.22, 2, 106, 'FÁBIÁN ILDIKÓ'),
(10, '1', '10', 57.3, 3, 178, 'BÁBICZKY LÁSZLÓ'),
(11, '2', '11', 57.3, 1, 178, 'APATÓCZKY ISTVÁNNÉ'),
(12, '2', '12', 35, 2, 109, 'SZUTOR GYULA & NEJE'),
(13, '2', '13', 57.3, 3, 178, 'VIRÁNYI ZSOLT'),
(14, '2', '14', 56.88, 2, 177, 'UDVARI JÓZSEF'),
(15, '2', '15', 34.22, 2, 106, 'NECKERNUSZ JÓZSEFNÉ'),
(16, '2', '16', 57.3, 2, 178, 'HRUBECZ GYÖRGY'),
(17, '3', '17', 57.3, 1, 178, 'RADVAN SÁNDORNÉ'),
(18, '3', '18', 35, 2, 109, 'HOTZ GABRIELLA'),
(19, '3', '19', 57.3, 3, 178, 'TÓVÁROSI LÁSZLÓ'),
(20, '3', '20', 56.88, 2, 177, 'SZULTOVSZKY KÁZMÉRNÉ'),
(21, '3', '21', 34.22, 2, 106, 'SZOLLÁR ANDRÁS'),
(22, '3', '22', 57.3, 2, 178, 'HELYEI LÁSZLÓ'),
(23, '4', '23', 57.3, 2, 178, 'HORVÁTH JÓZSEF'),
(24, '4', '24', 35, 1, 109, 'SATTLER MIKLÓSNÉ'),
(25, '4', '25', 57.3, 1, 178, 'MÁRKUS ÁDÁM'),
(26, '4', '26', 56.88, 3, 177, 'Dr. KISSNÉ NYULÁSZ MARGIT'),
(27, '4', '27', 34.22, 1, 106, 'VARGA NÓRA'),
(28, '4', '28', 57.3, 3, 178, 'KÖVESI ANDRÁS'),
(29, '5', '29', 57.3, 3, 178, 'SITTKEI TIBOR, MÁRFAI TÍMEA MÁRIA'),
(30, '5', '30', 35, 2, 109, 'FRANK PIROSKA'),
(31, '5', '31', 57.3, 3, 178, 'KÖKÉNY GYÖRGYI'),
(32, '5', '32', 56.88, 1, 177, 'HAVADI B. ERIKNÉ'),
(33, '5', '33', 34.22, 1, 106, 'CSISZÁR GÁBOR'),
(34, '5', '34', 57.3, 7, 178, 'BEING Kft.'),
(35, '6', '35', 57.3, 1, 178, 'KOJNOK ISTVÁNNÉ'),
(36, '6', '36', 35, 1, 109, 'GECSER MÓNIKA'),
(37, '6', '37', 57.3, 1, 178, 'RÓTH RICHÁRD'),
(38, '6', '38', 56.88, 2, 177, 'WEISZBURGNÉ, BIHARI JUDIT'),
(39, '6', '39', 34.22, 1, 106, 'DÉSI EDIT KRISZTINA'),
(40, '6', '40', 57.3, 2, 178, 'ORSZÁG JÓZSEF'),
(41, '7', '41', 57.3, 2, 178, 'CSETÉNÉ TACHER GABRIELLA'),
(42, '7', '42', 35, 1, 109, 'MÉSZÁROSNÉ NYULÁSZ ANNA'),
(43, '7', '43', 57.3, 3, 178, 'GÁL ELISABETA'),
(44, '7', '44', 56.88, 3, 177, 'DÖMÖK ILDIKÓ'),
(45, '7', '45', 34.22, 1, 106, 'FELSZERFALVI ISTVÁN'),
(46, '7', '46', 57.3, 2, 178, 'MÉSZÁROS I. FERENCNÉ MÉSZÁROS B. GYÖRGY'),
(47, '8', '47', 57.3, 2, 178, 'NAGY SZABOLCS'),
(48, '8', '48', 35, 1, 109, 'BERÉNYI KÁROLYNÉ'),
(49, '8', '49', 57.3, 2, 178, 'TÓTH PÁL ANDRÁS'),
(50, '8', '50', 56.88, 3, 177, 'BÁNFALVI PÉTER'),
(51, '8', '51', 34.22, 1, 106, 'SZABÓ ATTILA'),
(52, '8', '52', 57.3, 1, 178, 'CSORDÁS JÓZSEF'),
(53, '9', '53', 57.3, 2, 178, 'MÁRFAI RÓBERT & neje'),
(54, '9', '54', 35, 2, 109, 'GYURCSIK LÁSZLÓ & NEJE'),
(55, '9', '55', 57.3, 2, 178, 'FLEKÁCS JÓZSEFNÉ'),
(56, '9', '56', 56.88, 2, 177, 'BAKONDI ÁDÁM'),
(57, '9', '57', 34.22, 2, 106, 'CSÖKE ANDRÁS'),
(58, '9', '58', 57.3, 2, 178, 'TEMESVÁRY ÁGNES'),
(59, '10', '59', 57.3, 2, 178, 'PINTÉRNÉ L. KLÁRA'),
(60, '10', '60', 35, 2, 109, 'PILLER ZOLTÁN'),
(61, '10', '61', 57.3, 2, 178, 'BARTOS JÓZSEF'),
(62, '10', '62', 56.88, 1, 177, 'TAKÁCS MÁRTON'),
(63, '10', '63', 34.22, 2, 106, 'ANDA PÉTER'),
(64, '10', '64', 57.3, 1, 178, 'NAGY GYÖRGY JÓZSEF');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=3 ;

--
-- A tábla adatainak kiíratása `fees`
--

INSERT INTO `fees` (`id`, `name`, `yearly_amount`, `dealer`, `multiplier`) VALUES
(1, 'Közösköltség', 125, 821, '/terület'),
(2, 'Szemétdíj', 1250, 123, '/fő');

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
(1, 'Udvari', 'Szabolcs', 'szudvari@gmail.com', 'szudvari', '5420720b710f7f14d3d6d12c37c23a32032d14e08d6a13f613c775d28c388a31b3245d5458fda2d869153be6c0872118ef078df042f776210db7fdab8c46a5d3', 14, 1, 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
