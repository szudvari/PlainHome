-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Hoszt: 127.0.0.1
-- Létrehozás ideje: 2014. Jún 09. 11:51
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
(1, 'admin', '', '05db4ee4c4ebcb0c19571283be40bdceccb73cd83898519a6ed49c5754fafae46a87e2a14c98aba94f1668633471e149970a7544f93f36ef6bd7eb04f5539517', 99),
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
(4, 'fsz.', '4', 57.3, 2, 178, 'RAMASZ TIBOR & TIBORNÉ'),
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
-- Tábla szerkezet ehhez a táblához `deposit_balance`
--

CREATE TABLE IF NOT EXISTS `deposit_balance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deposit_id` int(11) NOT NULL,
  `year` year(4) NOT NULL,
  `opening_balance` int(11) NOT NULL,
  `actual_balance` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `deposit_id` (`deposit_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=65 ;

--
-- A tábla adatainak kiíratása `deposit_balance`
--

INSERT INTO `deposit_balance` (`id`, `deposit_id`, `year`, `opening_balance`, `actual_balance`) VALUES
(1, 1, 2014, -57479, -57479),
(2, 2, 2014, -11163, -11163),
(3, 3, 2014, 1556, 1556),
(4, 4, 2014, -3590, -3590),
(5, 5, 2014, 99, 99),
(6, 6, 2014, -29500, -29500),
(7, 7, 2014, -43667, -43667),
(8, 8, 2014, 0, 0),
(9, 9, 2014, -134006, -134006),
(10, 10, 2014, -85897, -85897),
(11, 11, 2014, 25, 25),
(12, 12, 2014, 2565, 2565),
(13, 13, 2014, -80739, -80739),
(14, 14, 2014, -81220, -81220),
(15, 15, 2014, -60444, -60444),
(16, 16, 2014, -19210, -19210),
(17, 17, 2014, 9, 9),
(18, 18, 2014, 12517, 12517),
(19, 19, 2014, -35697, -35697),
(20, 20, 2014, -6699, -6699),
(21, 21, 2014, 0, 0),
(22, 22, 2014, -3441, -3441),
(23, 23, 2014, -97, -97),
(24, 24, 2014, 0, 0),
(25, 25, 2014, -15763, -15763),
(26, 26, 2014, -59930, -59930),
(27, 27, 2014, -10125, -10125),
(28, 28, 2014, -10886, -10886),
(29, 29, 2014, -282137, -282137),
(30, 30, 2014, -6118, -6118),
(31, 31, 2014, -220801, -220801),
(32, 32, 2014, -8350, -8350),
(33, 33, 2014, -3729, -3729),
(34, 34, 2014, -323276, -323276),
(35, 35, 2014, -210, -210),
(36, 36, 2014, 12680, 12680),
(37, 37, 2014, -11548, -11548),
(38, 38, 2014, -24816, -24816),
(39, 39, 2014, -2082, -2082),
(40, 40, 2014, -55735, -55735),
(41, 41, 2014, -24295, -24295),
(42, 42, 2014, 75, 75),
(43, 43, 2014, -169997, -169997),
(44, 44, 2014, -16800, -16800),
(45, 45, 2014, -429, -429),
(46, 46, 2014, -903, -903),
(47, 47, 2014, 6654, 6654),
(48, 48, 2014, 50, 50),
(49, 49, 2014, 349, 349),
(50, 50, 2014, -11696, -11696),
(51, 51, 2014, -33251, -33251),
(52, 52, 2014, 28517, 28517),
(53, 53, 2014, -2360, -2360),
(54, 54, 2014, 481, 481),
(55, 55, 2014, -339863, -339863),
(56, 56, 2014, 57600, 57600),
(57, 57, 2014, 2799, 2799),
(58, 58, 2014, 21304, 21304),
(59, 59, 2014, 6, 6),
(60, 60, 2014, -111845, -111845),
(61, 61, 2014, -6022, -6022),
(62, 62, 2014, -19820, -19820),
(63, 63, 2014, -3416, -3416),
(64, 64, 2014, -33373, -33373);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `documents`
--

CREATE TABLE IF NOT EXISTS `documents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_hungarian_ci NOT NULL,
  `shortname` varchar(100) COLLATE utf8_hungarian_ci NOT NULL,
  `description` text COLLATE utf8_hungarian_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=6 ;

--
-- A tábla adatainak kiíratása `documents`
--

INSERT INTO `documents` (`id`, `name`, `shortname`, `description`) VALUES
(4, 'lion-ajcsi-bontas-100x150.jpg', 'A Zoroszlán ajándékot bont', 'Mert kíváncsi'),
(5, 'Eleon.pdf', 'Eleon', '');

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
(1, 'Közösköltség', 125, 3205, '/terület-egység'),
(2, 'Szemétdíj', 1250, 123, '/fő');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `payment`
--

CREATE TABLE IF NOT EXISTS `payment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deposit_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `amount` int(11) NOT NULL,
  `user` varchar(75) COLLATE utf8_hungarian_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `deposit_id` (`deposit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=5 ;

--
-- A tábla adatainak kiíratása `residents`
--

INSERT INTO `residents` (`id`, `firstname`, `lastname`, `email`, `username`, `password`, `depositid`, `active`, `admin`) VALUES
(2, 'Udvari', 'Szabolcs', 'szudvari@gmail.com', 'szudvari', '5420720b710f7f14d3d6d12c37c23a32032d14e08d6a13f613c775d28c388a31b3245d5458fda2d869153be6c0872118ef078df042f776210db7fdab8c46a5d3', 14, 1, 1),
(4, 'Udvari', 'Szabolcs', 'udvarisz@yahoo.com', 'szby', '5420720b710f7f14d3d6d12c37c23a32032d14e08d6a13f613c775d28c388a31b3245d5458fda2d869153be6c0872118ef078df042f776210db7fdab8c46a5d3', 11, 1, 1);

--
-- Megkötések a kiírt táblákhoz
--

--
-- Megkötések a táblához `deposit_balance`
--
ALTER TABLE `deposit_balance`
  ADD CONSTRAINT `deposit_balance_ibfk_1` FOREIGN KEY (`deposit_id`) REFERENCES `deposits` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`deposit_id`) REFERENCES `deposits` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `residents`
--
ALTER TABLE `residents`
  ADD CONSTRAINT `residents_ibfk_1` FOREIGN KEY (`depositid`) REFERENCES `deposits` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
