-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Počítač: 127.0.0.1
-- Vytvořeno: Sob 23. kvě 2015, 14:00
-- Verze serveru: 5.6.21
-- Verze PHP: 5.5.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Databáze: `zf1`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `singers`
--

CREATE TABLE IF NOT EXISTS `singers` (
`id` int(11) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `folder` varchar(255) NOT NULL,
  `mainimg` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Vypisuji data pro tabulku `singers`
--

INSERT INTO `singers` (`id`, `name`, `folder`, `mainimg`) VALUES
(1, 'Dzhena', 'Dzhena', 'dzhena-official.jpg'),
(2, 'ABBA', 'ABBA', 'abba.jpg'),
(3, 'Sandra', 'Sandra', 'Sandra_Cretu.jpg');

-- --------------------------------------------------------

--
-- Struktura tabulky `song_galleries`
--

CREATE TABLE IF NOT EXISTS `song_galleries` (
`imgid` int(11) unsigned NOT NULL,
  `gallerysongid` int(11) unsigned NOT NULL,
  `galleryimg` varchar(255) NOT NULL,
  `galleryimgalt` varchar(255) NOT NULL,
  `sort` int(10) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

--
-- Vypisuji data pro tabulku `song_galleries`
--

INSERT INTO `song_galleries` (`imgid`, `gallerysongid`, `galleryimg`, `galleryimgalt`, `sort`) VALUES
(1, 1, '1.jpg', 'To je alt test', 4),
(2, 2, 'abba-1.jpg', 'abba-1.jpg', 3),
(3, 1, '2.jpg', 'kkjkj', 5),
(4, 1, '4.png', 'jkhih', 1),
(5, 1, '5.jpg', 'song 5', 7),
(6, 1, '6.jpg', 'song 6', 6),
(7, 1, '7.jpg', 'song 7', 2),
(8, 1, '8.jpg', 'song 8', 3),
(9, 1, '9.jpg', 'song 9', 8),
(10, 3, 'sandra-1.jpg', 'Sandra 1', 1),
(11, 3, 'sandra-2.jpg', 'Sandra 2', 4),
(12, 3, 'sandra-3.jpg', 'sandra-3.jpg', 2),
(13, 3, 'sandra-4.jpg', 'sandra-4.jpg', 3),
(14, 2, 'abba-2.jpg', 'abba-2.jpg', 2),
(15, 2, 'abba-3.jpg', 'abba-3.jpg', 1),
(16, 2, 'abba-4.jpg', 'abba-4.jpg', 4);

--
-- Klíče pro exportované tabulky
--

--
-- Klíče pro tabulku `singers`
--
ALTER TABLE `singers`
 ADD PRIMARY KEY (`id`);

--
-- Klíče pro tabulku `song_galleries`
--
ALTER TABLE `song_galleries`
 ADD PRIMARY KEY (`imgid`), ADD KEY `gallerybookid` (`gallerysongid`), ADD KEY `gallerysongid` (`gallerysongid`);

--
-- AUTO_INCREMENT pro tabulky
--

--
-- AUTO_INCREMENT pro tabulku `singers`
--
ALTER TABLE `singers`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT pro tabulku `song_galleries`
--
ALTER TABLE `song_galleries`
MODIFY `imgid` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=17;
--
-- Omezení pro exportované tabulky
--

--
-- Omezení pro tabulku `song_galleries`
--
ALTER TABLE `song_galleries`
ADD CONSTRAINT `song_galleries_ibfk_1` FOREIGN KEY (`gallerysongid`) REFERENCES `singers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
