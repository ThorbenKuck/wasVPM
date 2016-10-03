-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Erstellungszeit: 02. Okt 2016 um 16:36
-- Server-Version: 5.7.15-0ubuntu0.16.04.1
-- PHP-Version: 7.0.8-0ubuntu0.16.04.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `vpm`
--
CREATE DATABASE IF NOT EXISTS `vpm` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `vpm`;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Logins`
--

CREATE TABLE IF NOT EXISTS `Logins` (
  `id` int(255) NOT NULL,
  `Hash` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `Logins`
--

INSERT INTO `Logins` (`id`, `Hash`) VALUES
(1, 'GqS&ixYR6SfoorCu/&.Uz7"bWB');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(25) NOT NULL,
  `username` varchar(255) NOT NULL,
  `passhash` varchar(255) NOT NULL,
  `permissions` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `users`
--

INSERT INTO `users` (`id`, `username`, `passhash`, `permissions`) VALUES
(1, 'test', '$2y$10$LdB./iqRPoorxoGincFVpu2jOdtBQPIw3Bwly6eO7KBAvWN0NM5nG', 9999);

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `Logins`
--
ALTER TABLE `Logins`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`username`),
  ADD UNIQUE KEY `id` (`id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `users`
--
ALTER TABLE `users`
  MODIFY `id` int(25) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
