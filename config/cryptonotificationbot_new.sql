-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Nov 05, 2023 alle 18:32
-- Versione del server: 10.4.17-MariaDB
-- Versione PHP: 8.0.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cryptonotificationbot_new`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `cryn_cryptocurrencies`
--

CREATE TABLE `cryn_cryptocurrencies` (
  `crypto_id` varchar(5) NOT NULL COMMENT 'related to coinmarketapi',
  `crypto_name` varchar(16) NOT NULL,
  `crypto_available_from` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Trigger `cryn_cryptocurrencies`
--
DELIMITER $$
CREATE TRIGGER `on_new_crypto` AFTER INSERT ON `cryn_cryptocurrencies` FOR EACH ROW BEGIN
   INSERT INTO cryn_notifications (user_idtelegram, crypto_id)
	SELECT user_idtelegram, NEW.crypto_id FROM cryn_users;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struttura della tabella `cryn_notifications`
--

CREATE TABLE `cryn_notifications` (
  `user_idtelegram` int(13) NOT NULL,
  `crypto_id` varchar(5) NOT NULL,
  `notify_status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 on, 0 off'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `cryn_users`
--

CREATE TABLE `cryn_users` (
  `user_idtelegram` int(13) NOT NULL,
  `user_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 is active, 0 is not',
  `user_expirationdate` date DEFAULT NULL COMMENT 'null means EVER',
  `user_minutesinterval` smallint(6) NOT NULL DEFAULT 120,
  `user_timeleft_notify` smallint(6) NOT NULL DEFAULT 0,
  `user_lastaction_datetime` datetime NOT NULL DEFAULT current_timestamp(),
  `user_processname` varchar(32) DEFAULT NULL,
  `user_silent_notifies` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 means silent, 0 not silent'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Trigger `cryn_users`
--
DELIMITER $$
CREATE TRIGGER `on_new_user` AFTER INSERT ON `cryn_users` FOR EACH ROW BEGIN
  INSERT INTO cryn_notifications (user_idtelegram, crypto_id)
  SELECT NEW.user_idtelegram, crypto_id FROM cryn_cryptocurrencies;
END
$$
DELIMITER ;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `cryn_cryptocurrencies`
--
ALTER TABLE `cryn_cryptocurrencies`
  ADD PRIMARY KEY (`crypto_id`);

--
-- Indici per le tabelle `cryn_notifications`
--
ALTER TABLE `cryn_notifications`
  ADD PRIMARY KEY (`user_idtelegram`,`crypto_id`),
  ADD KEY `crypto_id` (`crypto_id`);

--
-- Indici per le tabelle `cryn_users`
--
ALTER TABLE `cryn_users`
  ADD PRIMARY KEY (`user_idtelegram`);

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `cryn_notifications`
--
ALTER TABLE `cryn_notifications`
  ADD CONSTRAINT `cryn_notifications_ibfk_1` FOREIGN KEY (`user_idtelegram`) REFERENCES `cryn_users` (`user_idtelegram`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cryn_notifications_ibfk_2` FOREIGN KEY (`crypto_id`) REFERENCES `cryn_cryptocurrencies` (`crypto_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
