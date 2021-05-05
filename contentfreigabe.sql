-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 05. Mai 2021 um 12:03
-- Server-Version: 10.1.37-MariaDB
-- PHP-Version: 7.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `contentfreigabe`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `department`
--

CREATE TABLE `department` (
  `department_id` int(11) NOT NULL,
  `dep_name` varchar(25) COLLATE utf8_german2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_german2_ci;

--
-- Daten für Tabelle `department`
--

INSERT INTO `department` (`department_id`, `dep_name`) VALUES
(1, 'VoIP-Services'),
(2, 'Broad Band'),
(3, 'Wireless Net');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `pdfpermissions`
--

CREATE TABLE `pdfpermissions` (
  `pdfPermissions_id` int(11) NOT NULL,
  `pdf_id_fk` int(11) NOT NULL,
  `user_id_fk` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_german2_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `pdfs`
--

CREATE TABLE `pdfs` (
  `pdf_id` int(11) NOT NULL,
  `pdf_name` varchar(25) COLLATE utf8_german2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_german2_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `php_session`
--

CREATE TABLE `php_session` (
  `session_id` int(11) NOT NULL,
  `session_value` varchar(100) COLLATE utf8_german2_ci NOT NULL,
  `user_id_fk` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_german2_ci;

--
-- Daten für Tabelle `php_session`
--

INSERT INTO `php_session` (`session_id`, `session_value`, `user_id_fk`) VALUES
(1, '$2y$10$.uEQWiwV8gRWeGWxPtUS0.dRJoG7SboNkND9LQQWHKgpuUbjYpO5m', 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_german2_ci NOT NULL,
  `password` varchar(100) COLLATE utf8_german2_ci NOT NULL,
  `usergroup_id_fk` int(11) NOT NULL,
  `department_id_frk` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_german2_ci;

--
-- Daten für Tabelle `user`
--

INSERT INTO `user` (`user_id`, `name`, `password`, `usergroup_id_fk`, `department_id_frk`) VALUES
(1, 'admin', '$2y$10$.uEQWiwV8gRWeGWxPtUS0.dRJoG7SboNkND9LQQWHKgpuUbjYpO5m', 1, NULL),
(2, 'kikii', '$2y$10$8M6y/8xs7KZ6ZMGjhCoVUeRuEoJAPUFtaaz0pckC7KQAkBqabkZ2a', 2, 2),
(4, 'jule', '$2y$10$LB185wb0XhcZnnmMeeWIoOExOupuDafHfgdxYHCGULcflbt6BegG6', 2, 2),
(5, 'kylo', '$2y$10$TGj23T8Ru/BjB33Lfq5zXeWxHOObZDYtwIGQqi2B5JvZ.eZew7zvy', 3, NULL),
(6, 'vanny', '$2y$10$er4woMAm3mvy52drtY5kZeSei2r5qVpL6belnm7J.IZMMpUM1Is4u', 1, 1),
(8, 'majestic', '$2y$10$9aU0NeH.1.avb9sRKrwTQOnYQ.Az.1aI3HsfrgjW.al6dUCzvkmi2', 2, 2);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `usergroup`
--

CREATE TABLE `usergroup` (
  `usergroup_id` int(11) NOT NULL,
  `groupname` varchar(11) COLLATE utf8_german2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_german2_ci;

--
-- Daten für Tabelle `usergroup`
--

INSERT INTO `usergroup` (`usergroup_id`, `groupname`) VALUES
(1, 'Admin'),
(2, 'Employee'),
(3, 'Customer');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `video`
--

CREATE TABLE `video` (
  `video_id` int(11) NOT NULL,
  `video_name` varchar(50) COLLATE utf8_german2_ci NOT NULL,
  `security-level` int(11) NOT NULL,
  `plays` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_german2_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `videopermissions`
--

CREATE TABLE `videopermissions` (
  `user_video_id` int(11) NOT NULL,
  `video_id_frk` int(11) NOT NULL,
  `user_id_frk` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_german2_ci;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`department_id`);

--
-- Indizes für die Tabelle `pdfpermissions`
--
ALTER TABLE `pdfpermissions`
  ADD PRIMARY KEY (`pdfPermissions_id`),
  ADD KEY `user_id_fkey` (`user_id_fk`),
  ADD KEY `pdf_id_fk` (`pdf_id_fk`);

--
-- Indizes für die Tabelle `pdfs`
--
ALTER TABLE `pdfs`
  ADD PRIMARY KEY (`pdf_id`);

--
-- Indizes für die Tabelle `php_session`
--
ALTER TABLE `php_session`
  ADD PRIMARY KEY (`session_id`),
  ADD KEY `user_id_fk` (`user_id_fk`);

--
-- Indizes für die Tabelle `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `department_id_frk` (`department_id_frk`),
  ADD KEY `usergroup_id_fk` (`usergroup_id_fk`);

--
-- Indizes für die Tabelle `usergroup`
--
ALTER TABLE `usergroup`
  ADD PRIMARY KEY (`usergroup_id`);

--
-- Indizes für die Tabelle `video`
--
ALTER TABLE `video`
  ADD PRIMARY KEY (`video_id`);

--
-- Indizes für die Tabelle `videopermissions`
--
ALTER TABLE `videopermissions`
  ADD PRIMARY KEY (`user_video_id`),
  ADD KEY `user_id_frk` (`user_id_frk`),
  ADD KEY `video_id_frk` (`video_id_frk`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `department`
--
ALTER TABLE `department`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT für Tabelle `pdfpermissions`
--
ALTER TABLE `pdfpermissions`
  MODIFY `pdfPermissions_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `pdfs`
--
ALTER TABLE `pdfs`
  MODIFY `pdf_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `php_session`
--
ALTER TABLE `php_session`
  MODIFY `session_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT für Tabelle `usergroup`
--
ALTER TABLE `usergroup`
  MODIFY `usergroup_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT für Tabelle `video`
--
ALTER TABLE `video`
  MODIFY `video_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `videopermissions`
--
ALTER TABLE `videopermissions`
  MODIFY `user_video_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `pdfpermissions`
--
ALTER TABLE `pdfpermissions`
  ADD CONSTRAINT `pdf_id_fk` FOREIGN KEY (`pdf_id_fk`) REFERENCES `pdfs` (`pdf_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `user_id_fkey` FOREIGN KEY (`user_id_fk`) REFERENCES `user` (`user_id`) ON UPDATE CASCADE;

--
-- Constraints der Tabelle `php_session`
--
ALTER TABLE `php_session`
  ADD CONSTRAINT `user_id_fk` FOREIGN KEY (`user_id_fk`) REFERENCES `user` (`user_id`) ON UPDATE CASCADE;

--
-- Constraints der Tabelle `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `department_id_frk` FOREIGN KEY (`department_id_frk`) REFERENCES `department` (`department_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `usergroup_id_fk` FOREIGN KEY (`usergroup_id_fk`) REFERENCES `usergroup` (`usergroup_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `videopermissions`
--
ALTER TABLE `videopermissions`
  ADD CONSTRAINT `user_id_frk` FOREIGN KEY (`user_id_frk`) REFERENCES `user` (`user_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `video_id_frk` FOREIGN KEY (`video_id_frk`) REFERENCES `video` (`video_id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
