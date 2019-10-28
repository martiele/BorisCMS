-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Creato il: Ott 28, 2019 alle 13:49
-- Versione del server: 5.7.23
-- Versione PHP: 7.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `roncuccipartners`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `dny_file_generati`
--

CREATE TABLE `dny_file_generati` (
  `idc` int(10) UNSIGNED NOT NULL,
  `id_sezione` int(10) UNSIGNED NOT NULL,
  `nome` varchar(255) NOT NULL DEFAULT '',
  `foglio` varchar(100) DEFAULT NULL,
  `codicedoganalepg0` varchar(300) DEFAULT NULL,
  `descrizionepg0` text,
  `schedapaesepg1` varchar(100) DEFAULT NULL,
  `riga1pg2` varchar(100) DEFAULT NULL,
  `riga2pg2` varchar(100) DEFAULT NULL,
  `descrizionepg3` text,
  `annodapg4` varchar(10) DEFAULT NULL,
  `annoapg4` varchar(10) DEFAULT NULL,
  `percentualepg5` varchar(100) DEFAULT NULL,
  `descrizione1pg6` text,
  `descrizione2pg6` text,
  `descrizione3pg6` text,
  `titolo` varchar(255) DEFAULT NULL,
  `sottotitolo` varchar(255) DEFAULT NULL,
  `descrizione` text,
  `fileimg` varchar(255) DEFAULT NULL,
  `file_generato` varchar(300) DEFAULT NULL,
  `ordinamento` float UNSIGNED NOT NULL DEFAULT '1',
  `eliminato` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `is_attivo` tinyint(3) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `dny_file_generati`
--

INSERT INTO `dny_file_generati` (`idc`, `id_sezione`, `nome`, `foglio`, `codicedoganalepg0`, `descrizionepg0`, `schedapaesepg1`, `riga1pg2`, `riga2pg2`, `descrizionepg3`, `annodapg4`, `annoapg4`, `percentualepg5`, `descrizione1pg6`, `descrizione2pg6`, `descrizione3pg6`, `titolo`, `sottotitolo`, `descrizione`, `fileimg`, `file_generato`, `ordinamento`, `eliminato`, `created`, `modified`, `is_attivo`) VALUES
(1, 1, 'Notizia n. 12', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Titolo notizia uno2', 'Sottotitolo notizia uno2', '<p>Descrizione notizia 12</p>', '1_1031019Modello.xlsx', 'gen_20191007164844_1_1031019Modello.xlsx', 1, 1, NULL, '2019-10-07 16:48:44', 1),
(2, 1, 'prova', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'titolo prova', 'sottotitolo prova', '<p>descrizione prova</p>', NULL, NULL, 2, 1, NULL, NULL, 0),
(3, 1, 'prova', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'titolo prova', 'sottotitolo prova', '<p><strong>descrizione</strong> provadas</p>\r\n<p>ds</p>\r\n<p><span style=\"font-family: mceinline;\">ad</span></p>\r\n<p>asd</p>\r\n<p>&nbsp;</p>\r\n<p>as</p>\r\n<h2>d</h2>', '3_031019Modello.xlsx', 'gen_20191007165522_031019Modello.xlsx', 3, 1, NULL, '2019-10-07 16:56:20', 1),
(4, 6, 'Prova 123', 'scheda01', '160415', 'Preparazioni e conserve di tonni, palamite e boniti \r\n\"sarda spp.\" interi o in pezzi (escl. quelle tritate) test', NULL, 'I dati presentati fanno riferimento al periodo 2014-2019 (ultimi 5 anni) e al', '2° trimestre 2019 (ultimo trimestre).', 'Codice Doganale HS Codice Doganale HS 160415 : Preparazioni e conserve di tonni, palamite e boniti \r\n\"sarda spp.\" interi o in pezzi (escl. quelle tritate) test', '2014', '2019', NULL, 'La Germania importa il prodotto selezionato per un valore pari a 519,6 milioni di Euro, posizionandosi come 1° paese importatore a livello mondiale (13,6% del totale). Lo score nella classifica dei mercati OPPORTUNITA’ è complessivamente positivo (798).  \r\n\r\nL’Italia esporta verso la Germania 8,9 milioni di Euro, rappresenta quindi il 2° mercato per l’export italiano del prodotto selezionato. Nel Paese è diretto il 18,1% del prodotto complessivo esportato dall’Italia e l’1,7% del prodotto importato è di provenienza italiana. In relazione a 100 euro di importazioni, la Germania esporta 202 Euro verso il mondo, si tratta quindi di un Paese che esporta il doppio di quanto importa. Per quanto riguarda l’Italia, la cifra relativa alle esportazioni verso il nostro Paese è 854 Euro ogni 100 Euro importati, 8,5 volte superiore.', 'Nella terza sezione sono riportate le variazioni delle importazioni dal mondo e dall’Italia. Le importazioni della Germania dal Mondo dal 2013 al 2018 sono aumentate del 5,8% di media all’anno, mentre quelle dall’Italia sono cresciute del 38,9%. Ancora, è stato registrato un trend del +00,00% nell\'ultimo anno, del +00,00% nell\'ultimo trimestre con un trend ponderato complessivo del +00,00%.\r\n\r\nNella quarta sezione si evince che la crescita dell’export mondiale verso la Germania è inferiore rispetto al trend di crescita medio. Al contrario la crescita delle esportazioni italiane del prodotto selezionato verso il Paese, è maggiore rispetto al trend medio dell’export italiano verso il mondo. Non sono presenti dazi per chi esporta il prodotto selezionato in Germania, né dall’Italia né dall’estero. Le previsioni a breve termine riportano un mercato che continuerà a crescere in maniera significativa.', 'La quinta e ultima sezione riporta il valore medio unitario, ovvero un chilo di prodotto italiano esportato in Germania ha un valore medio di 17,21 Euro, inferiore rispetto alla media di 28,60 Euro delle importazioni dagli altri Paesi. La Germania è un mercato assolutamente sicuro, sia dal punto di vista della sicurezza paese che di sicurezza del credito.\r\n\r\nInfine la Germania risulta essere un mercato DEFENSE per l’Italia, ovvero si tratta di un Paese molto dinamico per l’Italia, meno per il mondo. La crescita quindi, evidenziata in precedenza, si prevede proseguirà nei prossimi anni.', NULL, NULL, NULL, '4_Esempio2.xlsx', '20191009100733_Esempio1.xlsx', 4, 0, '2019-10-07 15:40:27', '2019-10-28 11:13:10', 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `dny_log`
--

CREATE TABLE `dny_log` (
  `id` int(11) NOT NULL,
  `id_utente` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `descrizione` varchar(255) NOT NULL DEFAULT '',
  `tabella` varchar(50) NOT NULL DEFAULT '',
  `id_record` int(10) UNSIGNED DEFAULT NULL,
  `deleted` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `dny_log`
--

INSERT INTO `dny_log` (`id`, `id_utente`, `descrizione`, `tabella`, `id_record`, `deleted`, `created`, `modified`) VALUES
(1, 0, 'Aggiunto', 'foto', 1, 0, '2019-09-18 13:39:08', NULL),
(2, 0, 'Aggiunto', 'gruppifoto', 1, 0, '2019-09-18 13:39:35', NULL),
(3, 0, 'Modificato', 'settings', 1, 0, '2019-09-18 13:42:26', NULL),
(4, 0, 'Modificato', 'settings', 8, 0, '2019-09-18 13:42:32', NULL),
(5, 0, 'Modificato', 'foto', 1, 0, '2019-09-18 14:25:01', NULL),
(6, 0, 'Modificato', 'foto', 1, 0, '2019-09-18 14:25:38', NULL),
(7, 0, 'Modificato', 'foto', 1, 0, '2019-09-18 14:25:54', NULL),
(8, 0, 'Modificato', 'foto', 1, 0, '2019-09-18 14:31:06', NULL),
(9, 0, 'Aggiunto', 'foto', 3, 0, '2019-09-18 14:38:13', NULL),
(10, 0, 'Modificato', 'foto', 3, 0, '2019-09-18 14:39:18', NULL),
(11, 0, 'Modificato', 'sezioni', 1, 0, '2019-09-19 09:52:14', NULL),
(12, 0, 'Aggiunto', 'sezioni', 2, 0, '2019-09-19 09:52:20', NULL),
(13, 0, 'Aggiunto', 'sezioni', 3, 0, '2019-09-19 09:53:11', NULL),
(14, 0, 'Modificato', 'sezioni', 1, 0, '2019-09-19 09:53:37', NULL),
(15, 0, 'Modificato', 'foto', 1, 0, '2019-09-19 10:09:03', NULL),
(16, 0, 'Modificato', 'foto', 3, 0, '2019-09-19 10:10:08', NULL),
(17, 0, 'Modificato', 'sezioni', 1, 0, '2019-09-19 13:31:06', NULL),
(18, 0, 'Modificato', 'sezioni', 1, 0, '2019-09-19 13:31:33', NULL),
(19, 0, 'Modificato', 'sezioni', 1, 0, '2019-09-19 14:40:11', NULL),
(20, 0, 'Modificato', 'sezioni', 1, 0, '2019-09-19 14:40:21', NULL),
(21, 0, 'Modificato', 'foto', 3, 0, '2019-09-19 14:41:56', NULL),
(22, 0, 'Modificato', 'foto', 3, 0, '2019-09-19 14:44:43', NULL),
(23, 0, 'Modificato', 'sezioni', 1, 0, '2019-09-19 14:58:10', NULL),
(24, 0, 'Modificato', 'sezioni', 1, 0, '2019-09-19 14:59:40', NULL),
(25, 0, 'Modificato', 'sezioni', 1, 0, '2019-09-19 15:04:15', NULL),
(26, 0, 'Modificato', 'sezioni', 1, 0, '2019-09-19 15:04:35', NULL),
(27, 0, 'Modificato', 'sezioni', 1, 0, '2019-09-20 09:40:15', NULL),
(28, 0, 'Modificato', 'foto', 1, 0, '2019-09-23 14:09:46', NULL),
(29, 0, 'Modificato', 'foto', 1, 0, '2019-09-23 14:15:14', NULL),
(30, 0, 'Modificato', 'foto', 1, 0, '2019-09-23 14:25:17', NULL),
(31, 0, 'Modificato', 'settings', 25, 0, '2019-10-07 13:06:15', NULL),
(32, 0, 'Modificato', 'modelli', 1, 0, '2019-10-07 13:12:52', NULL),
(33, 0, 'Modificato', 'modelli', 1, 0, '2019-10-07 13:13:03', NULL),
(34, 0, 'Modificato', 'modelli', 1, 0, '2019-10-07 13:14:19', NULL),
(35, 0, 'Modificato', 'modelli', 1, 0, '2019-10-07 13:15:03', NULL),
(36, 0, 'Modificato', 'modelli', 1, 0, '2019-10-07 13:18:20', NULL),
(37, 0, 'Modificato', 'modelli', 1, 0, '2019-10-07 13:21:06', NULL),
(38, 0, 'Modificato', 'modelli', 1, 0, '2019-10-07 13:24:32', NULL),
(39, 0, 'Aggiunto', 'modelli', 4, 0, '2019-10-07 13:28:33', NULL),
(40, 0, 'Modificato', 'modelli', 4, 0, '2019-10-07 13:28:43', NULL),
(41, 0, 'Modificato', 'settings', 1, 0, '2019-10-07 13:52:44', NULL),
(42, 0, 'Modificato', 'settings', 25, 0, '2019-10-07 13:54:01', NULL),
(43, 0, 'Modificato', 'settings', 19, 0, '2019-10-07 14:00:09', NULL),
(44, 0, 'Modificato', 'settings', 19, 0, '2019-10-07 14:09:45', NULL),
(45, 0, 'Aggiunto', 'settings', 28, 0, '2019-10-07 14:10:08', NULL),
(46, 0, 'Modificato', 'settings', 28, 0, '2019-10-07 14:10:20', NULL),
(47, 0, 'Modificato', 'files', 1, 0, '2019-10-07 14:45:46', NULL),
(48, 0, 'Modificato', 'files', 1, 0, '2019-10-07 14:53:38', NULL),
(49, 0, 'Modificato', 'files', 3, 0, '2019-10-07 14:54:24', NULL),
(50, 0, 'Aggiunto', 'files', 4, 0, '2019-10-07 15:40:27', NULL),
(51, 0, 'Modificato', 'modelli', 1, 0, '2019-10-07 16:17:15', NULL),
(52, 0, 'Modificato', 'files', 1, 0, '2019-10-07 16:17:23', NULL),
(53, 0, 'Modificato', 'settings', 25, 0, '2019-10-07 16:36:34', NULL),
(54, 0, 'Modificato', 'modelli', 1, 0, '2019-10-07 16:38:20', NULL),
(55, 0, 'Modificato', 'modelli', 1, 0, '2019-10-07 16:38:32', NULL),
(56, 0, 'Modificato', 'files', 1, 0, '2019-10-07 16:45:14', NULL),
(57, 0, 'Modificato', 'files', 1, 0, '2019-10-07 16:45:48', NULL),
(58, 0, 'Modificato', 'files', 1, 0, '2019-10-07 16:46:24', NULL),
(59, 0, 'Modificato', 'files', 1, 0, '2019-10-07 16:46:44', NULL),
(60, 0, 'Modificato', 'files', 1, 0, '2019-10-07 16:48:44', NULL),
(61, 0, 'Modificato', 'files', 3, 0, '2019-10-07 16:51:32', NULL),
(62, 0, 'Modificato', 'modelli', 1, 0, '2019-10-07 16:52:19', NULL),
(63, 0, 'Modificato', 'files', 3, 0, '2019-10-07 16:52:33', NULL),
(64, 0, 'Modificato', 'files', 3, 0, '2019-10-07 16:55:22', NULL),
(65, 0, 'Modificato', 'files', 3, 0, '2019-10-07 16:56:20', NULL),
(66, 0, 'Modificato', 'files', 4, 0, '2019-10-07 16:56:48', NULL),
(67, 0, 'Modificato', 'files', 4, 0, '2019-10-09 08:26:35', NULL),
(68, 0, 'Modificato', 'files', 4, 0, '2019-10-09 10:04:02', NULL),
(69, 0, 'Modificato', 'files', 4, 0, '2019-10-09 10:05:19', NULL),
(70, 0, 'Modificato', 'modelli', 1, 0, '2019-10-09 10:07:22', NULL),
(71, 0, 'Modificato', 'files', 4, 0, '2019-10-09 10:07:33', NULL),
(72, 0, 'Modificato', 'files', 4, 0, '2019-10-10 14:22:07', NULL),
(73, 0, 'Modificato', 'modelli', 1, 0, '2019-10-10 14:24:13', NULL),
(74, 0, 'Modificato', 'modelli', 1, 0, '2019-10-10 14:29:57', NULL),
(75, 0, 'Modificato', 'files', 4, 0, '2019-10-10 15:13:30', NULL),
(76, 0, 'Aggiunto', 'modelli', 5, 0, '2019-10-24 14:13:33', NULL),
(77, 0, 'Modificato', 'files', 4, 0, '2019-10-24 14:13:43', NULL),
(78, 0, 'Modificato', 'modelli', 5, 0, '2019-10-24 14:50:50', NULL),
(79, 0, 'Modificato', 'files', 4, 0, '2019-10-24 16:28:34', NULL),
(80, 0, 'Modificato', 'files', 4, 0, '2019-10-24 16:29:18', NULL),
(81, 0, 'Modificato', 'files', 4, 0, '2019-10-24 16:29:28', NULL),
(82, 0, 'Aggiunto', 'modelli', 6, 0, '2019-10-25 13:58:21', NULL),
(83, 0, 'Modificato', 'modelli', 6, 0, '2019-10-28 09:15:22', NULL),
(84, 0, 'Modificato', 'modelli', 5, 0, '2019-10-28 09:15:28', NULL),
(85, 0, 'Modificato', 'modelli', 1, 0, '2019-10-28 09:15:31', NULL),
(86, 0, 'Modificato', 'files', 4, 0, '2019-10-28 11:00:16', NULL),
(87, 0, 'Modificato', 'files', 4, 0, '2019-10-28 11:13:10', NULL);

-- --------------------------------------------------------

--
-- Struttura della tabella `dny_modelli`
--

CREATE TABLE `dny_modelli` (
  `ids` int(10) UNSIGNED NOT NULL,
  `nome` varchar(255) NOT NULL DEFAULT '',
  `titolo_pagina` varchar(64) DEFAULT NULL,
  `descrizione_pagina` text,
  `citazione_start` varchar(500) DEFAULT NULL,
  `autore_start` varchar(100) DEFAULT NULL,
  `descrizione_bottom` text,
  `citazione_end` varchar(500) DEFAULT NULL,
  `autore_end` varchar(100) DEFAULT NULL,
  `url` varchar(30) DEFAULT NULL,
  `zoom` varchar(10) NOT NULL DEFAULT '1.0',
  `speed` int(11) NOT NULL DEFAULT '5',
  `bgcolor` varchar(30) DEFAULT NULL,
  `title` varchar(250) DEFAULT NULL,
  `description` text,
  `keywords` varchar(250) DEFAULT NULL,
  `gruppo` varchar(20) NOT NULL DEFAULT 'comunicazione',
  `mostra_in_home` tinyint(4) NOT NULL DEFAULT '0',
  `ordinamento_home` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `fileimg` varchar(100) DEFAULT NULL,
  `mostra_in_menu` tinyint(4) NOT NULL DEFAULT '0',
  `ordinamento` float UNSIGNED NOT NULL DEFAULT '1',
  `eliminato` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `is_attivo` tinyint(3) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `dny_modelli`
--

INSERT INTO `dny_modelli` (`ids`, `nome`, `titolo_pagina`, `descrizione_pagina`, `citazione_start`, `autore_start`, `descrizione_bottom`, `citazione_end`, `autore_end`, `url`, `zoom`, `speed`, `bgcolor`, `title`, `description`, `keywords`, `gruppo`, `mostra_in_home`, `ordinamento_home`, `fileimg`, `mostra_in_menu`, `ordinamento`, `eliminato`, `created`, `modified`, `is_attivo`) VALUES
(1, 'Modello dati base', 'Tutte le news della scuola in diretta!', 'Modello XLSX di prova inviato da Nicolò il 3 ottobre 2019', NULL, NULL, 'Contatti o informazioni da mostrare in fondo alla pagina', NULL, NULL, 'tal-de-tali', '1.0', 6, '#e6f411', NULL, NULL, NULL, 'standard', 0, 1, '1_081019ModelloDRIVERcorretto.xlsx', 0, 1, 0, '2019-09-18 13:12:11', '2019-10-28 09:15:31', 0),
(4, 'Prova', NULL, 'ecco una breve descrizione', NULL, NULL, NULL, NULL, NULL, NULL, '1.0', 5, NULL, NULL, NULL, NULL, 'comunicazione', 0, 0, NULL, 0, 2, 1, '2019-10-07 13:28:33', '2019-10-07 13:28:43', 0),
(5, 'Nuovo Mod Def', NULL, 'Modello semidefinitivo Nicolò', NULL, NULL, NULL, NULL, NULL, NULL, '1.0', 5, NULL, NULL, NULL, NULL, 'comunicazione', 0, 0, '5_211019DRIVERModellodef.xlsx', 0, 2, 0, '2019-10-24 14:13:33', '2019-10-28 09:15:28', 0),
(6, 'DEF', NULL, 'Definitivo 25/11/2019', NULL, NULL, NULL, NULL, NULL, NULL, '1.0', 5, NULL, NULL, NULL, NULL, 'comunicazione', 0, 0, '6_10ModelloDefinitivo.xlsx', 0, 3, 0, '2019-10-25 13:58:21', '2019-10-28 09:15:22', 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `dny_site_setting`
--

CREATE TABLE `dny_site_setting` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL DEFAULT '',
  `valore` text,
  `level` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
  `deleted` tinyint(3) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `dny_site_setting`
--

INSERT INTO `dny_site_setting` (`id`, `nome`, `valore`, `level`, `deleted`) VALUES
(1, 'www_title', 'R&P Generatore Reports', 1, 0),
(2, 'globalDomainUrl', 'localhost', 1, 0),
(5, 'globalLogoUrl', 'resources/images/logo.png', 1, 0),
(6, 'globalCompleteUrl', 'http://localhost/presideproject', 1, 0),
(8, 'footer', 'KIOSK - Preside Project - info footer', 1, 1),
(11, 'path_upload_admin', '../public/', 1, 0),
(12, 'path_upload', 'public/', 1, 0),
(14, 'path_foto_prodotto', 'prodotto/', 1, 1),
(16, 'path_fotobig_prodotto', 'big/', 1, 1),
(17, 'path_fotooriginal_prodotto', 'lavori/', 1, 1),
(19, 'path_filecaricati', 'files_caricati/', 1, 0),
(20, 'titolo_news', 'NEWS', 2, 1),
(21, 'sottotitolo_news', 'Febbraio 2012', 2, 1),
(23, 'path_fotooriginal_slides', 'slides/', 1, 1),
(24, 'path_fotooriginal_slides_thumb', 'slides_thumb/', 1, 1),
(25, 'path_filemodelli', 'modelli/', 1, 0),
(26, 'path_fotohome_small', 'home/', 1, 1),
(27, 'path_fotooriginal_slides_med', 'slides_med/', 1, 1),
(28, 'path_filegenerati', 'files_generati/', 1, 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `dny_template_table`
--

CREATE TABLE `dny_template_table` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL DEFAULT '',
  `ordinamento` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `deleted` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `dny_utente`
--

CREATE TABLE `dny_utente` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(50) NOT NULL DEFAULT '',
  `pswd` varchar(50) NOT NULL DEFAULT '',
  `level` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
  `ordinamento` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `is_attivo` tinyint(3) UNSIGNED DEFAULT '1',
  `deleted` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `dny_utente`
--

INSERT INTO `dny_utente` (`id`, `nome`, `email`, `pswd`, `level`, `ordinamento`, `is_attivo`, `deleted`, `created`, `modified`) VALUES
(1, 'Daniele Martini', 'ing.martini@gmail.com', 'eccolala', 1, 0, 1, 0, NULL, NULL),
(10, 'Aiosa Web Agency', 'info@aiosa.net', 'FammiEntrare00!!', 1, 4, 1, 0, NULL, NULL),
(12, 'Nicolò', 'n.pietanesi@roncucciandpartners.com', 'roncpart12', 2, 6, 1, 0, NULL, NULL);

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `dny_file_generati`
--
ALTER TABLE `dny_file_generati`
  ADD PRIMARY KEY (`idc`);

--
-- Indici per le tabelle `dny_log`
--
ALTER TABLE `dny_log`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `dny_modelli`
--
ALTER TABLE `dny_modelli`
  ADD PRIMARY KEY (`ids`);

--
-- Indici per le tabelle `dny_site_setting`
--
ALTER TABLE `dny_site_setting`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- Indici per le tabelle `dny_template_table`
--
ALTER TABLE `dny_template_table`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `dny_utente`
--
ALTER TABLE `dny_utente`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `dny_file_generati`
--
ALTER TABLE `dny_file_generati`
  MODIFY `idc` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT per la tabella `dny_log`
--
ALTER TABLE `dny_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT per la tabella `dny_modelli`
--
ALTER TABLE `dny_modelli`
  MODIFY `ids` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT per la tabella `dny_site_setting`
--
ALTER TABLE `dny_site_setting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT per la tabella `dny_template_table`
--
ALTER TABLE `dny_template_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `dny_utente`
--
ALTER TABLE `dny_utente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
