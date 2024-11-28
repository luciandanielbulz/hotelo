-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Erstellungszeit: 28. Nov 2024 um 15:45
-- Server-Version: 10.11.6-MariaDB-0+deb12u1
-- PHP-Version: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `venditio_db`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cashflows`
--

CREATE TABLE `cashflows` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `transactiondate` date NOT NULL,
  `partnername` varchar(255) DEFAULT NULL,
  `partneriban` varchar(34) DEFAULT NULL,
  `bic_swift` varchar(11) DEFAULT NULL,
  `partneracountnumber` varchar(34) DEFAULT NULL,
  `bankcode` varchar(10) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` char(3) NOT NULL,
  `transactiondetails` text DEFAULT NULL,
  `transactionreference` varchar(255) DEFAULT NULL,
  `ownaccountname` varchar(255) DEFAULT NULL,
  `owniban` varchar(34) DEFAULT NULL,
  `paymentmethod` enum('Überweisung','Kartenzahlung') NOT NULL DEFAULT 'Überweisung',
  `transactiontype` enum('Einnahme','Ausgabe','Info') NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `factor` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `clients`
--

CREATE TABLE `clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `clientname` varchar(100) DEFAULT NULL,
  `companyname` varchar(100) DEFAULT NULL,
  `business` varchar(60) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `postalcode` varchar(10) DEFAULT NULL,
  `location` varchar(30) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(40) DEFAULT NULL,
  `tax_id` bigint(20) UNSIGNED NOT NULL,
  `webpage` varchar(300) DEFAULT NULL,
  `bank` varchar(30) DEFAULT NULL,
  `accountnumber` varchar(30) DEFAULT NULL,
  `vat_number` varchar(15) DEFAULT NULL,
  `bic` varchar(30) DEFAULT NULL,
  `smallbusiness` tinyint(1) NOT NULL DEFAULT 0,
  `logo` varchar(100) DEFAULT NULL,
  `logoheight` varchar(100) DEFAULT NULL,
  `logowidth` varchar(100) DEFAULT NULL,
  `signature` varchar(500) DEFAULT NULL,
  `style` varchar(11) DEFAULT '1',
  `lastoffer` int(11) NOT NULL,
  `offermultiplikator` int(11) NOT NULL,
  `lastinvoice` int(11) NOT NULL,
  `invoicemultiplikator` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `clients`
--

INSERT INTO `clients` (`id`, `clientname`, `companyname`, `business`, `address`, `postalcode`, `location`, `email`, `phone`, `tax_id`, `webpage`, `bank`, `accountnumber`, `vat_number`, `bic`, `smallbusiness`, `logo`, `logoheight`, `logowidth`, `signature`, `style`, `lastoffer`, `offermultiplikator`, `lastinvoice`, `invoicemultiplikator`, `created_at`, `updated_at`) VALUES
(1, 'Lucian-Daniel Bulz', 'Ing. Lucian-Daniel Bulz', 'Kleinunternehmen', 'Neue-Welt-Gasse 3', '8600', 'Bruck an der Mur', 'office@bulz.at', '0664 35 67 645', 1, 'www.bulz.at', 'Sparkasse', 'AT71 2081 5000 0470 4425', NULL, 'STSPAT2GXXX', 1, 'assets/logo.png', '100', '100', 'MFG\r\nBulz Lucian', '1', 1, 10000, 24, 10000, '2024-11-25 10:31:37', '2024-11-26 12:59:37');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `conditions`
--

CREATE TABLE `conditions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `conditionname` varchar(30) NOT NULL,
  `daysskonto` int(11) NOT NULL DEFAULT 0,
  `skonto` int(11) NOT NULL DEFAULT 0,
  `daysnetto` int(11) NOT NULL DEFAULT 0,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `conditions`
--

INSERT INTO `conditions` (`id`, `conditionname`, `daysskonto`, `skonto`, `daysnetto`, `client_id`, `created_at`, `updated_at`) VALUES
(1, 'keine', 0, 0, 0, 1, NULL, NULL),
(2, '2% 7Tage', 7, 2, 14, 1, NULL, NULL),
(3, '14Tage netto', 0, 0, 14, 1, NULL, NULL),
(4, 'Prompt', 0, 0, 0, 1, NULL, NULL),
(5, '14Tage Netto', 0, 0, 14, 1, NULL, NULL),
(6, 'Vorauskasse', 0, 0, 0, 1, NULL, NULL),
(7, '7Tage Netto', 0, 0, 7, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `customers`
--

CREATE TABLE `customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `companyname` varchar(100) DEFAULT NULL,
  `customername` varchar(100) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `postalcode` varchar(10) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `phone` varchar(100) DEFAULT NULL,
  `fax` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `vat_number` varchar(255) DEFAULT NULL,
  `tax_id` bigint(20) UNSIGNED NOT NULL,
  `condition_id` bigint(20) UNSIGNED NOT NULL,
  `salutation_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(50) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `emailsubject` varchar(200) DEFAULT NULL,
  `emailbody` varchar(1000) DEFAULT NULL,
  `issoftdeleted` tinyint(1) NOT NULL DEFAULT 0,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `customers`
--

INSERT INTO `customers` (`id`, `companyname`, `customername`, `address`, `postalcode`, `location`, `country`, `phone`, `fax`, `email`, `vat_number`, `tax_id`, `condition_id`, `salutation_id`, `title`, `active`, `emailsubject`, `emailbody`, `issoftdeleted`, `client_id`, `created_at`, `updated_at`) VALUES
(1, 'Dummy', 'Dummy', 'Dummy', '', '', '', '', '', '', '', 1, 1, 1, '', 1, '', '', 0, 1, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(2, 'KRICKON Projektmanagement & Projektentwicklung GmbH', '', 'Vogelsanggasse 19', '1050', 'Wien', '', '+43 1 934 67 85', '', 'office@krickon.at', '', 1, 4, 3, '', 1, '', '', 0, 1, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(3, 'ADG-Elektrotechnik', 'David Gagea', 'Oberlaaerstraße 61', '2333', 'Leopoldsdorf', '', '', '', 'office@adg-elektrotechnik.com', '', 1, 1, 1, '', 1, '', '', 0, 1, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(4, '', 'Ing. Wallner Günther', 'Bergbaustraße 2a', '8600', 'Bruck', 'Österreich', '', '', 'guenther.wallner@e-steiermark.com', '', 1, 1, 1, '', 1, '', '', 0, 1, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(5, 'Ordination Dr.Gagea', 'Camelia-Vasilica Gagea', 'Strindberggasse 2/6/3', '1110', 'Wien', 'Österreich', '', '', 'office@ordination11wien.at', '', 1, 1, 3, '', 1, '', '', 0, 1, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(6, '', 'Mag. Samuel Krupensky', 'Nussalee 16', '2402', 'Maria Elend', '', '', '', 'office@krupensky-immobilien.at', '', 1, 4, 1, '', 1, '', '', 0, 1, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(7, 'LOS Dienstleistungs GmbH', 'Daniel Suingiu', 'Herzog-Ernst-Gasse 1', '8600', 'Bruck', 'Österreich', '06764020504', '', 'daniel.suingiu@icloud.com', 'ATU68580788', 1, 3, 1, '', 1, '', '', 0, 1, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(8, 'Chira Bau GmbH', 'Adrian Chira', 'Feldgasse 4', '8662', 'St.Barbara i. Mürztal', 'Österreich', '', '', 'adrian.chira@gmx.at', '', 1, 3, 1, '', 1, '', '', 0, 1, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(9, '', 'Alfred Mairzedt', 'Rohrlingweg 2', '4611', 'Buchkirchen', '', '', '', 'alfred.mairzedt@gmail.com', '', 1, 5, 1, '', 1, '', '', 0, 1, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(10, 'Ordination Dr. Rus', 'Dr. med. dent. Diana-Margareta Rus', 'Alxingergasse 89-91/4/8', '1100', 'Wien', '', '01 6032627', '', 'kontakt@zahnarzt-dr-rus.com', '', 1, 1, 2, '', 1, 'Rechnung für das Quartal {Quartal}', 'Hallo Dorin, \r\n\r\nanbei die Rechnung für die Wartung des Quartals {Quartal}\r\n\r\nLG und vielen Dank\r\n\r\nLucian Bulz', 0, 1, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(11, '', 'Patrick Supp', 'Bienengasse 16a', '8501', 'Lieboch', '', '', '', 'ps@supp.at', '', 1, 5, 1, '', 1, '', '', 0, 1, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(12, 'Kunde', '', 'Adresse', '8600', 'Bruck', '', '', '', '', '', 1, 1, 1, '', 1, '', '', 0, 1, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(13, '', 'Beni Circeie', '---', '--', '---', '', '', '', '', '', 1, 5, 1, '', 1, '', '', 0, 1, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(14, '', 'Dr. Maria-Magdalena Laszlo', 'Bachgasse 3/1', '1160', 'Wien', '', '', '', 'dr.laszlomagdalena@gmail.com', '', 1, 5, 2, '', 1, 'Rechnung Wartung {Monat}', 'Hallo Magda,\r\nanbei die Rechnung für die Wartung für den Monat {Monat}\r\n\r\nLG\r\n\r\nLucian Bulz', 0, 1, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(15, '', 'Dr. Elisabeth Skodler', 'Hauptstraße 33', '2452', 'Mannersdorf', '', '02168/62324', '', 'post@leithabergmed.at', '', 1, 1, 2, '', 1, '', '', 0, 1, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(16, '', 'Silke Duder', 'Kastaniengasse 5', '2402', 'Maria Ellend', '', '', '', '', '', 1, 3, 2, '', 1, '', '', 0, 1, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(17, '', 'Alexandra Izdebesky', 'Huschkagasse 4/4/3', '1190', 'Wien', '', '', '', '', '', 1, 1, 1, '', 1, '', '', 0, 1, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(18, '', 'Costache Suingiu', 'Ritting 4', '8600', 'Bruck', '', '', '', '', '', 1, 1, 1, '', 1, '', '', 0, 1, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(19, '', 'Frank Dorner', 'Oberlaaerstraße 61', '2333', 'Leopoldsdorf', '', '', '', 'fm.dorner@variuscard.com', '', 1, 1, 1, '', 1, '', '', 0, 1, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(20, '', 'Ari Rashid', 'Voltagsee 43/14/6', '1210', 'Wien', '', '', '', 'office@linos.co.at', 'ATU 74186169', 1, 3, 1, '', 1, '', '', 0, 1, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(21, '', 'Bmstr Ing Martin Sieger', 'Baumgasse 42/12A', '1030', 'Wien', '', '', '', 'martin.sieger@chello.at', '', 1, 1, 1, '', 1, '', '', 0, 1, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(22, 'Duft Unternehmensgruppe', '', 'Alte Salzstraße 97', '88171', 'Weiler-Simmerberg', 'Deutschland', '', '', 'duft-vertrieb@t-online.de', 'DE361446856', 1, 5, 3, '', 1, '', '', 0, 1, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(23, 'Raylyst Solar s.r.o', '', 'Türkova 2319/5b', '14900', 'Praha 4', 'Tschechien', '', '', 'vitezslav.hajek@raylyst.eu', 'CZ07259557', 1, 1, 1, '', 1, '', '', 0, 1, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(24, 'Hotel Josefshof Ges.m.b.H.', 'z.H. Hr. Franz Honegger', 'Josefsgasse 4-8', '1080', 'Wien', '', '', '', 'franz.honegger@josefshof.com', 'ATU 16166609', 1, 1, 1, '', 1, '', '', 0, 1, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(25, '', 'Alexandru Rus', 'Lindenring 3', '2325', 'Pellendorf', '', '', '', '', '', 1, 3, 1, '', 1, '', '', 0, 1, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(26, 'Ordination Dr. Gabriela-Cristina Sas', 'Gabriela-Cristina Sas', 'Alserbachstraße 10A/1-2', '1090', 'Wien', 'Österreich', '01 31 01 921', NULL, 'cristinasas2000@yahoo.com', NULL, 1, 1, 2, 'Dr. med.', 0, NULL, NULL, 0, 1, '2024-11-25 19:24:36', '2024-11-25 19:24:36');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `factorrules`
--

CREATE TABLE `factorrules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `descriptionpattern` varchar(255) NOT NULL,
  `factor` decimal(5,2) NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fileuploads`
--

CREATE TABLE `fileuploads` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `filename` varchar(300) NOT NULL,
  `filetempname` varchar(300) NOT NULL,
  `filesize` varchar(200) NOT NULL,
  `fileerrors` varchar(300) DEFAULT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  `processed` tinyint(1) NOT NULL DEFAULT 0,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `invoicepositions`
--

CREATE TABLE `invoicepositions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `designation` varchar(255) DEFAULT NULL,
  `details` varchar(2000) DEFAULT NULL,
  `unit_id` bigint(20) UNSIGNED NOT NULL DEFAULT 1,
  `price` decimal(10,2) NOT NULL,
  `positiontext` tinyint(1) NOT NULL DEFAULT 0,
  `sequence` int(11) DEFAULT NULL,
  `issoftdeleted` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `invoicepositions`
--

INSERT INTO `invoicepositions` (`id`, `invoice_id`, `amount`, `designation`, `details`, `unit_id`, `price`, `positiontext`, `sequence`, `issoftdeleted`, `created_at`, `updated_at`) VALUES
(1, 1, 1.00, 'Geräte und Kabel', '2 Stk Loxonekabeln á 100m\r\n2 Stk KNX Kabel á 100m\r\n7 Stk Lautsprecherboxen (Holz)\r\n2 Stk Touch Surface Tree\r\n1 Stk LED Dimmer Tree\r\n1 Stk LED Streifen RGBW', 2, 1316.00, 0, 0, 0, NULL, NULL),
(22, 20, 1.00, 'Loxone Komponente', 'Rohrmotor Solid 20Nm AIR\r\nAdapter\r\nRGBW Dimmer AIR\r\nSmart Socket AIR 2Stück\r\nWassersensor AIR', 2, 820.07, 0, 0, 0, NULL, NULL),
(24, 20, 1.00, 'Rabatt', 'Rabatt 25% auf Rohrmotoren, RGBW Dimmer, Smart Socket, Wassersensor\r\nRabatt 10% auf Adapter\r\nRabatt 0% Versandkosten', 2, -201.48, 0, 0, 0, NULL, NULL),
(25, 21, 1.00, 'Doorbird Türsprecheinrichtung', '', 1, 385.00, 0, 0, 0, NULL, NULL),
(29, 25, 1.00, 'NFC-Code Touch 100300', 'Inkl. Versand', 1, 250.00, 0, 0, 0, NULL, NULL),
(31, 27, 1.00, 'Loxone Komponente', 'Touch Air White\r\nGeiger Solid Air\r\nAdapter\r\nVersandkostenpauschale', 2, 377.35, 0, 0, 0, NULL, NULL),
(32, 27, 1.00, 'Rabatt', '10% Rabatt auf die Komponenten', 1, -33.77, 0, 0, 0, NULL, NULL),
(33, 28, 1.00, 'Jahrespauschale', 'Servicepauschale für die Servisierung der Server, PCs und Software', 2, 6416.00, 0, 0, 0, NULL, NULL),
(35, 30, 1.00, 'Loxone Geräte', '1Stk Multi Extension Air\r\n1Stk RGBW 24V Compact Dimmer Air\r\n1Stk SMA Stabantenne 4dBi 868 MHz\r\n4Stk 1-Wire Hülsen Temperaturfühler\r\n1Stk Knopfzelle CR2450H (20 Stk.)\r\nVersandpauschale', 2, 762.26, 0, 0, 0, NULL, NULL),
(36, 30, 1.00, '10% Rabatt', '', 1, -76.23, 0, 0, 0, NULL, NULL),
(37, 31, 1.00, 'EDV-Dienstleistungen', 'Jahresservicepauschale für die Betreuung der Systeme und Unterstützung für Rechnungsstellungssysteme\r\nServisierung Homepage', 2, 2945.00, 0, 0, 0, NULL, NULL),
(38, 32, 1.00, 'Abrechnung Projekt', 'Umstellung Telefonanlage\r\nErweiterung des Internetanschlusses\r\nProgrammierung und Eingliederung des Laptops zur Verwendung im Netzwerk der Ordination', 2, 3268.00, 0, 0, 0, NULL, NULL),
(39, 33, 1.00, 'Loxone Geräte', '-Intercom der Generation 2 ArtNr.:100484\r\n     S/N: 50:4F:94:E0:3E:02\r\n-Rahmen passend ArtNr.: 100487\r\n-Versandpauschale', 2, 556.00, 0, 0, 0, NULL, NULL),
(50, 39, 1.00, 'Telefonanlage', 'Telefonanlage mit Kommunikationsserver für Digitale Amtsleitungen ISDN Basis, inklusive folgener Geräte und Zubehör:\r\n\r\n - 1 Stk Kommunikationsserver SMBC für die Montage im Rack-Schrank\r\n      + 8 Digitale Nebenstellenanschlüsse\r\n      + 6 Analoge Nebenstellenanschlüsse\r\n      + Basic Voice Mail\r\n      + 1 Text vor Melden, Musik im Wartezustand, …\r\r\n      + Analoge Nebenstelle für bestehendes Faxgerät (1 Stk.)\r\n - 6 Digitale Endgeräte\r\n - 1 Interface für Warteraum inkl. Verstärker für Lautsprecher\r\n - nötige Verbindungskabeln im Schrank\r\n - Lizenzen für den Betrieb\r\n - Verteilertätigkeiten bzw. Verkabelung der Telefonanlage\r\n - Inbetriebnahme des Kommunikationssystems \r\n\r\nLieferung nach Montage der Möbel, Termin wird noch ausgemacht', 2, 0.00, 0, 0, 0, NULL, NULL),
(51, 39, 1.00, 'Telefonanlage', 'Schlussrechnung abzüglich 50% Anzahlung.\r\nFür die Telefonanlage aus Angebot 20226015', 2, 4606.00, 0, 0, 0, NULL, '2024-11-25 12:20:39'),
(52, 40, 1.00, 'Energiemessung', 'Energiemessung mit 2 Punkten inkl. Verfoltung \r\n     und Logging per App\r\nLoxone Miniserver Go,\r\nNetzteil\r\nVerbrauchszähler 3 Phasen\r\nEthernet Modbus RTU Konverter\r\nMontage und Kabel Pauschale\r\nNetzwerkkabel Vorbereitung für das Auslesen \r\n     der Daten im Schrank\r\nMontage Stecker LAN in die Fassungen inkl Material', 2, 1792.00, 0, 0, 0, NULL, NULL),
(53, 41, 1.00, 'Netzwerkkomponente', '-Digitus 26HE Dynamic Basic Serverschrank\r\n-DIGITUS Patchpanel - 19-Zoll\r\n-Cat 7 Kabel\r\n-Cat 7 Aufputzdosen mit Anschlüssen\r\n-Kabelkanal\r\n-Kabelführung 19\" \r\n-Kabeldurchführungen\r\n-Fachböden\r\n-Netzwerkkabel 0,25m\r\n-Netzwerkkabel 2m\r\n-Netzwerkkabel 1m', 2, 1020.00, 0, 0, 0, NULL, NULL),
(54, 42, 1.00, 'Parametrierung Loxone', 'Fehleranalyse und Parametrierung', 2, 40.00, 0, 0, 0, NULL, NULL),
(57, 43, 1.00, 'Lizenzen', '1Stk Windows Server 2019 Standard Lizenz\r\n5Stk ClientLizenzen\r\n1Stk Windows 10 Lizenz (für Virtuellen PC)\r\n1Stk Office 2019 Lizenz (für Virtuellen PC)\r\n1Stk Backup Software mit Rotation der Festplatten', 1, 1384.00, 0, 0, 0, NULL, NULL),
(66, 44, 1.00, 'Installationskabel', 'Installationskabel für Ordination', 2, 270.00, 0, 0, 0, NULL, NULL),
(67, 45, 1.00, 'Backuplizenz inkl. Einrichtung und Tests', 'Backupassist Classic für Server inkl. 1 Jahr\r\nAktualisierungsgarantie\r\nLizenzzertifikat folgt', 2, 454.54, 0, 0, 0, NULL, NULL),
(69, 47, 1.00, 'Festplatten für Server', '4Stk Festplatten für Server mit je 4Tb Speicherplatz \r\nFür den Einbau in den Server ML30 geeignet.\r\nErsatzbestellung für die stornierten Platten bei BA-Computer', 2, 853.00, 0, 0, 0, NULL, NULL),
(70, 48, 1.00, 'Netzwerkkomponente', '100m Netzzwerkkabel\r\nNetzwerkschrank 10 Zoll\r\nAccessPoint für Deckeneinbau', 2, 289.37, 0, 0, 0, NULL, NULL),
(93, 51, 1.00, 'Loxone Komponente', '1x Touch Tree anth.\r\n1x Präsensmelder Tree anth.\r\n1x Smart Socket Air', 2, 301.80, 0, 0, 0, NULL, NULL),
(95, 51, 1.00, 'Rabatt', '10% Rabatt auf die Komponenten', 2, -30.18, 0, 0, 0, NULL, NULL),
(96, 52, 1.00, 'Raumklima Sensor', '', 2, 103.22, 0, 0, 0, NULL, NULL),
(97, 52, 1.00, '10% Rabatt', '', 2, -10.32, 0, 0, 0, NULL, NULL),
(98, 53, 1.00, 'EDV-Dienstleistungen', 'Jahresservicepauschale für die Betreuung der Systeme und Unterstützung für Rechnungsstellungssysteme Servisierung Homepage', 2, 3239.00, 0, 0, 0, NULL, NULL),
(99, 54, 1.00, 'Jahresservicepauschale', 'Servicepauschale für die Servisierung der Server, PCs und Software', 1, 4235.00, 0, 0, 0, NULL, NULL),
(102, 57, 3.00, 'Portable Festplatten à 3TB', '', 1, 104.38, 0, 0, 0, NULL, NULL),
(103, 57, 2.00, 'Funkt Tastatur + Maus', '', 1, 60.55, 0, 0, 0, NULL, NULL),
(104, 57, 1.00, 'Lizenzen Office', '4 Lizenzen für Office 2016 Standard', 2, 163.20, 0, 0, 0, NULL, NULL),
(105, 25, 3.00, 'Portable Festplatten à 3TB', '', 1, 104.38, 0, 0, 0, NULL, NULL),
(106, 25, 2.00, 'Funkt Tastatur + Maus', '', 1, 60.55, 0, 0, 0, NULL, NULL),
(107, 57, 0.00, '', 'Sämtliche Positionen wurden bereits ausgeliefert; Diese Rechnung bezieht sich rein auf die bestellten Produkte', 3, 0.00, 1, 0, 0, NULL, NULL),
(108, 58, 1.00, 'Solaranlage', 'Wechselrichter Huawei SUN2000-KTL10-M1\r\nSmart Meter', 2, 1832.00, 0, 0, 0, NULL, NULL),
(109, 58, 1.00, 'Versandpauschale', '', 1, 54.00, 0, 0, 0, NULL, NULL),
(126, 64, 1.00, 'Telefon', 'Austausch Telefon inkl. Programmierung Rekonfiguration per Fernwartung und Konfiguration vor Ort', 2, 343.00, 0, 0, 0, NULL, NULL),
(127, 64, 1.00, 'Rabatt', 'Der geschätzte Arbeitsaufwand für die Konfiguration hat sich vermindert dadurch ergibt sich eine reduzierung', 2, -50.00, 0, 0, 0, NULL, NULL),
(129, 66, 1.00, 'Wechselrichter und Restmaterial', '', 2, 1570.00, 0, 0, 0, NULL, NULL),
(130, 67, 1.00, 'Wechselrichter inkl. Zubehör', '', 2, 2824.74, 0, 0, 0, NULL, NULL),
(131, 68, 1.00, 'Bezeichnung ändern', 'Generatoranschlusskasten 2 String', 1, 556.57, 0, 0, 0, NULL, NULL),
(132, 69, 1.00, 'Wartungspauschale', 'Wartungspauschale aus Vertrag für April 2023', 2, 212.00, 0, 0, 0, NULL, NULL),
(133, 70, 1.00, 'Wartung 2.Quartal 2023', '', 2, 464.67, 0, 0, 0, NULL, NULL),
(134, 71, 1.00, 'Komponente PV', '1Stk    Neutralsammler\r\n2Stk    PE-Klemmen inkl. Deckel\r\n50m    Aderleitung Gelb/Grün\r\n8Stk    Kabelschuh M10 - 16mm²\r\n22m    PV-Kabel Solar rot\r\n22m    PV-Kabel Solar schwarz\r\n10Stk  Verschraubungen für Generatoranschlusskasten\r\n2m       Kabelkanal 10cm\r\n25m    Netzwerkkabel\r\n2Stk    Netzwerkstecker\r\n8Stk    PV-Stecker\r\n2Stk    Alubögen\r\n1Stk    Switch\r\n1Stk    AccessPoint\r\n1Stk    Netzwerkinjektor\r\n\r\nSwitch, Injektor und AccessPoint werden nachgeliefert', 2, 675.84, 0, 0, 0, NULL, NULL),
(135, 72, 1.00, 'Generatoranschlusskasten', '', 2, 568.00, 0, 0, 0, NULL, NULL),
(136, 73, 1.00, 'Wartungspauschale', 'Wartungspauschale aus Vertrag für Mai 2023', 2, 212.00, 0, 0, 0, NULL, NULL),
(137, 74, 1.00, 'Wartungspauschale', 'Wartungspauschale aus Vertrag für Juni 2023', 2, 212.00, 0, 0, 0, NULL, NULL),
(138, 75, 1.00, 'Administration IT-Infrastruktur', 'Betreuung der IT-Anlage', 2, 2000.00, 0, 0, 0, NULL, NULL),
(139, 76, 1.00, 'PC', 'HP Pro Gen9, I5, 256 Gb SSD\r\nMonitor AOC 22\"\r\nWandhalterung\r\nOffice 2019 Proffesional Plus\r\nNetzwerkkabel und Videokabel\r\nParametrierung des PC und Eingliederung in Domäne\r\nWandmontage', 2, 1130.00, 0, 0, 0, NULL, NULL),
(140, 76, 1.00, 'Telefone', 'Telefontausch Ordinationen 1,2,3 und Büro\r\nParametrierung der Anlage, Konfiguration neue Tastenbelegung', 2, 200.00, 0, 0, 0, NULL, NULL),
(141, 77, 1.00, 'PV-Anlage', '1Stk Huawei Wechselrichter inkl. Dongle 11kVA (10kWp)\r\n1Stk Smartmeter\r\n1Stk Generatoranschlusskasten \r\n16m Solarkabel 6mm²\r\n16m Erdungskabel 16mm²\r\n10Stk Schraubklemmen\r\n10Stk Profile\r\n6Stk Verbinder Profile\r\n60Stk Schrauben mit Muttern\r\n36Stk Dachhacken\r\n20Stk PV Zwischenverbinder\r\n14Stk PV Endverbinder\r\n10Stk Stecker MC4', 2, 2256.43, 0, 0, 0, NULL, NULL),
(142, 78, 1.00, 'Wartung 3.Quartal 2023', '', 2, 464.67, 0, 0, 0, NULL, NULL),
(143, 79, 1.00, 'Wartungspauschale', 'Wartungspauschale aus Vertrag für Juli 2023', 2, 212.00, 0, 0, 0, NULL, NULL),
(144, 80, 1.00, 'Alu Rohre und Verbinungsmuffen', 'Materialabholung bei Schäcke am 20.07.2023 und am 25.07.2023', 2, 109.56, 0, 0, 0, NULL, NULL),
(145, 81, 1.00, '1.Teilzahlung Angebot Cafe Goldstück', 'Angebot von 21.5.2023', 2, 1434.10, 0, 0, 0, NULL, NULL),
(147, 83, 1.00, 'Wartung', 'Wartungspauschale aus Vertrag für Juli 2023', 2, 212.00, 0, 0, 0, NULL, NULL),
(148, 84, 1.00, 'Wartung', 'Wartungspauschale aus Vertrag für September 2023', 2, 212.00, 0, 0, 0, NULL, NULL),
(149, 85, 1.00, 'Wartungspauschale', 'Wartungspauschale aus Vertrag für Oktober 2023', 2, 212.00, 0, 0, 0, NULL, NULL),
(150, 86, 1.00, 'Huawei DTSU666-H SENSOR', '', 2, 190.00, 0, 0, 0, NULL, NULL),
(151, 87, 1.00, 'Wartungspauschale', 'Wartungspauschale aus Vertrag für November 2023', 2, 212.00, 0, 0, 0, NULL, NULL),
(152, 88, 1.00, 'Wartungspauschale', 'Wartungspauschale aus Vertrag für Dezember 2023', 2, 212.00, 0, 0, 0, NULL, NULL),
(153, 89, 1.00, 'Provision für Verkauf von Waren', '', 2, 976.00, 0, 0, 0, NULL, NULL),
(154, 90, 1.00, 'Jahresservicepauschale', 'Servicepauschale für die Servisierung der Server, PCs und Software', 2, 3082.00, 0, 0, 0, NULL, NULL),
(155, 91, 1.00, 'Wartungspauschale', 'Wartungspauschale aus Vertrag für Jänner 2024', 2, 212.00, 0, 0, 0, NULL, NULL),
(156, 92, 1.00, 'Mehler 1653172', 'AP-Installations-Flachverteiler + RW Einsatz 3EBS (102TE)', 2, 300.16, 0, 0, 0, NULL, NULL),
(157, 93, 0.00, '', 'Indexanpassung auf Basis VPI 2010 - Oktober. Basis für die Berechnung: Oktober bis Oktober', 3, 0.00, 1, 0, 0, NULL, NULL),
(158, 93, 1.00, 'Wartungspauschale', 'Wartungspauschale aus Vertrag für Februar 2024', 2, 223.24, 0, 0, 0, NULL, NULL),
(159, 94, 1.00, 'Rechnungsbetrag Cafe Goldstück', '', 2, 5735.00, 0, 0, 0, NULL, NULL),
(161, 94, 0.00, '', 'Schlussrechnung Cafe Goldstück', 3, 0.00, 1, 0, 0, NULL, NULL),
(162, 95, 1.00, 'Wartungspauschale', 'Wartungspauschale aus Vertrag für März2024', 2, 223.24, 0, 0, 0, NULL, NULL),
(163, 96, 1.00, 'Zusatztätigkeiten Lichter Cafe Goldstück', '', 2, 1374.66, 0, 0, 0, NULL, NULL),
(164, 97, 1.00, 'Wartungspauschale', 'Wartungspauschale aus Vertrag für April 2024', 2, 223.24, 0, 0, 0, NULL, NULL),
(165, 98, 1.00, 'Zusatztätigkeiten Kaffee Goldstück', 'Einbau DimmerExtension, Umhängen der Lampen auf dimmbare Ausgänge', 2, 300.00, 0, 0, 0, NULL, NULL),
(166, 99, 1.00, 'Wartungspauschale', 'Wartungspauschale aus Vertrag für Mai 2024', 2, 223.24, 0, 0, 0, NULL, NULL),
(167, 100, 1.00, 'PV- Befestigungsmaterial', '20 Stk Stockschrauben M12x300\r\n60 Stk Winkelaufsatz\r\n20 Stk Profilverbinder\r\n50 Stk Endklemmen Panele\r\n100 Stk Mittelklemmen Panele\r\n27 Stk Trägerprofile 5,5m\r\nTransportpauschale', 2, 2154.14, 0, 0, 0, NULL, NULL),
(168, 101, 1.00, 'Wartungspauschale', 'Wartungspauschale aus Vertrag für Juni 2024', 2, 223.24, 0, 0, 0, NULL, NULL),
(169, 102, 1.00, 'PV-Montageteile Verrechnung', 'Smartmeter\r\nWinkel\r\nRohr 32mm à 3m\r\nRohrbogen 32mm\r\nGeneratoranschlusskasten\r\nVerdrahtungskanal', 2, 1035.64, 0, 0, 0, NULL, NULL),
(170, 103, 1.00, 'Wartungspauschale', 'Wartungspauschale aus Vertrag für Juli 2024', 2, 223.24, 0, 0, 0, NULL, NULL),
(171, 104, 1.00, 'Wartungspauschale', 'Wartungspauschale aus Vertrag für August 2024', 2, 223.24, 0, 0, 0, NULL, NULL),
(172, 105, 1.00, 'Wartungspauschale', 'Wartungspauschale aus Vertrag für September 2024', 2, 223.24, 0, 0, 0, NULL, NULL),
(173, 106, 1.00, 'Wartung', 'Pauschale Wartung für das 1 Halbjahr', 2, 301.00, 0, 0, 0, NULL, NULL),
(174, 106, 1.00, 'Stromversorgung Server', 'Erweiterung Stromversorgung Server', 2, 575.00, 0, 0, 0, NULL, NULL),
(175, 107, 1.00, 'Wechselrichter 10kW', 'inkl. Zubehör', 2, 1280.00, 0, 0, 0, NULL, NULL),
(176, 108, 1.00, 'Wartung EDV-Systeme', '', 2, 464.67, 0, 0, 0, NULL, NULL),
(177, 108, 1.00, 'Servererweiterung', '', 2, 65.00, 0, 0, 0, NULL, NULL),
(178, 109, 1.00, 'Wartungspauschale', 'Wartungspauschale aus Vertrag für Oktober 2024', 2, 223.24, 0, 0, 0, NULL, NULL),
(179, 110, 1.00, 'Montage Solar-WR', 'Baustelle Mramor', 2, 1305.64, 0, 0, 0, NULL, NULL),
(180, 111, 1.00, 'Wartungspauschale', 'Wartungspauschale aus Vertrag für November 2024', 2, 223.24, 0, 0, 0, NULL, NULL),
(181, 112, 1.00, 'Fertigstellung WR', 'Fertigstellung WR Klosterneuburg', 2, 2200.00, 0, 0, 0, NULL, NULL),
(182, 113, 1.00, 'Austausch Securitygateway', NULL, 2, 600.00, 0, 0, 0, NULL, '2024-11-27 05:02:59'),
(183, 114, 1.00, 'Backup Assist for Server', NULL, 2, 320.00, 0, 1, 0, '2024-11-25 19:24:52', '2024-11-26 12:35:58'),
(184, 115, 1.00, 'Austausch Unifi UCG', 'Austausch, Inbetriebnahme und Umtausch', 2, 288.25, 0, 1, 0, '2024-11-26 13:11:55', '2024-11-26 13:13:09'),
(185, 115, 1.00, '10% Rabatt', NULL, 2, -30.26, 0, 3, 0, '2024-11-26 13:13:13', '2024-11-26 13:14:16');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `invoices`
--

CREATE TABLE `invoices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp(),
  `number` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `tax_id` bigint(20) UNSIGNED NOT NULL,
  `taxburden` tinyint(1) DEFAULT NULL,
  `depositamount` decimal(10,2) DEFAULT NULL,
  `periodfrom` date DEFAULT NULL,
  `periodto` date DEFAULT NULL,
  `condition_id` bigint(20) UNSIGNED NOT NULL,
  `payed` tinyint(1) DEFAULT NULL,
  `payeddate` datetime DEFAULT NULL,
  `archived` tinyint(1) NOT NULL DEFAULT 0,
  `archiveddate` date DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `createddate` datetime NOT NULL DEFAULT current_timestamp(),
  `offer_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `invoices`
--

INSERT INTO `invoices` (`id`, `customer_id`, `date`, `number`, `description`, `tax_id`, `taxburden`, `depositamount`, `periodfrom`, `periodto`, `condition_id`, `payed`, `payeddate`, `archived`, `archiveddate`, `comment`, `createddate`, `offer_id`, `created_at`, `updated_at`) VALUES
(1, 2, '2019-11-05', '20191001', '', 1, 0, 0.00, NULL, NULL, 4, 1, NULL, 1, NULL, '', '2024-11-25 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(20, 4, '2020-11-19', '20201007', '', 1, 0, 0.00, NULL, NULL, 1, 0, NULL, 1, NULL, '', '2024-11-25 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(21, 6, '2020-12-10', '20201008', '', 1, 0, 0.00, NULL, NULL, 1, 0, NULL, 1, NULL, '', '2024-11-25 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(25, 9, '2021-02-02', '20211012', '', 1, 0, 0.00, NULL, NULL, 5, 1, NULL, 1, NULL, '', '2024-11-25 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(27, 4, '2021-07-13', '20211014', '', 1, 0, 0.00, NULL, NULL, 1, 1, NULL, 1, NULL, '', '2024-11-25 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(28, 5, '2021-09-29', '20211015', '', 1, 0, 0.00, NULL, NULL, 1, 1, NULL, 1, NULL, '', '2024-11-25 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(30, 4, '2021-11-29', '20211017', '', 1, 0, 0.00, NULL, NULL, 1, 1, NULL, 1, NULL, '', '2024-11-25 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(31, 8, '2021-11-29', '20211018', '', 1, 0, 0.00, NULL, NULL, 3, 1, NULL, 1, NULL, '', '2024-11-25 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(32, 5, '2021-12-02', '20211019', '', 1, 0, 0.00, NULL, NULL, 1, 1, NULL, 1, NULL, '', '2024-11-25 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(33, 11, '2021-12-27', '20211020', '', 1, 0, 0.00, NULL, NULL, 5, 1, NULL, 1, NULL, '', '2024-11-25 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(39, 10, '2022-06-30', '20221025', 'Telefonanlage Schlussrechnung', 1, 0, 2303.00, NULL, NULL, 1, 1, NULL, 0, NULL, 'Telefonanlage Schlussrechnung', '2024-11-25 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(40, 10, '2022-08-27', '20221026', 'Energiemessung in Ordination', 1, 0, 0.00, NULL, NULL, 1, 1, NULL, 0, NULL, 'Energiemessung in Ordination', '2024-11-25 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(41, 15, '2022-10-10', '20221027', 'Netzwerk', 1, 0, 0.00, NULL, NULL, 1, 1, NULL, 0, NULL, 'Netzwerk', '2024-11-25 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(42, 16, '2022-11-04', '20221028', 'Kastaniengasse 5', 1, 0, 0.00, NULL, NULL, 3, 1, NULL, 0, NULL, 'Kastaniengasse 5', '2024-11-25 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(43, 14, '2022-11-07', '20221029', 'Lizenzen', 1, 0, 0.00, NULL, NULL, 1, 1, NULL, 0, NULL, 'Lizenzen', '2024-11-25 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(44, 10, '2022-11-14', '20221030', 'Kabel', 1, 0, 0.00, NULL, NULL, 1, 1, NULL, 0, NULL, 'Kabel', '2024-11-25 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(45, 10, '2022-11-16', '20221031', 'Backup', 1, 0, 0.00, NULL, NULL, 4, 1, NULL, 0, NULL, 'Backup', '2024-11-25 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(47, 14, '2022-11-25', '20221033', 'Festplatten für Server', 1, 0, 0.00, NULL, NULL, 1, 1, NULL, 0, NULL, 'Festplatten für Server', '2024-11-25 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(48, 8, '2022-11-29', '20221034', 'Netzwerk', 1, 0, 0.00, NULL, NULL, 1, 1, NULL, 0, NULL, 'Netzwerk', '2024-11-25 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(51, 4, '2022-12-16', '20221036', 'Loxone Komponente', 1, 0, 0.00, NULL, NULL, 1, 1, NULL, 0, NULL, 'Loxone Komponente', '2024-11-25 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(52, 4, '2022-12-23', '20221037', 'Loxone Klima Sensor', 1, 0, 0.00, NULL, NULL, 1, 1, NULL, 0, NULL, 'Loxone Klima Sensor', '2024-11-25 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(53, 8, '2022-12-23', '20221038', 'EDV-Dienstleistungen', 1, 0, 0.00, NULL, NULL, 3, 1, NULL, 0, NULL, 'EDV-Dienstleistungen', '2024-11-25 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(54, 5, '2022-12-24', '20221039', 'Jahresservicepauschale', 1, 0, 0.00, NULL, NULL, 1, 1, NULL, 0, NULL, 'Jahresservicepauschale', '2024-11-25 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(57, 14, '2023-01-26', '20231041', 'Büromaterial', 1, 0, 0.00, NULL, NULL, 3, 1, NULL, 0, NULL, 'Büromaterial', '2024-11-25 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(58, 8, '2023-02-17', '20231042', 'Wechselrichter für Solaranlage', 1, 0, 0.00, NULL, NULL, 3, 1, NULL, 0, NULL, 'Wechselrichter für Solaranlage', '2024-11-25 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(64, 10, '2023-03-12', '20231044', '', 1, 0, 0.00, NULL, NULL, 2, 1, NULL, 0, NULL, 'Telefonerweiterung', '2024-11-25 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(66, 18, '2023-03-14', '20231046', '', 1, 0, 0.00, NULL, NULL, 1, 1, NULL, 0, NULL, 'WR und Zubehör', '2024-11-25 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(67, 8, '2023-03-23', '20231047', '', 1, 0, 0.00, NULL, NULL, 3, 1, NULL, 0, NULL, 'WR und Zubehör', '2024-11-25 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(68, 3, '2023-03-31', '202310471', '', 1, 0, 0.00, NULL, NULL, 1, 1, NULL, 0, NULL, 'Generatoranschlusskasten', '2024-11-25 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(69, 14, '2023-04-04', '20231048', '', 1, 0, 0.00, NULL, NULL, 4, 1, NULL, 0, NULL, 'Wartung April', '2024-11-25 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(70, 10, '2023-04-04', '20231049', 'EDV Wartung', 1, 0, 0.00, NULL, NULL, 4, 1, NULL, 0, NULL, 'Wartung Q2/23', '2024-11-25 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(71, 8, '2023-04-22', '20231050', '', 1, 0, 0.00, NULL, NULL, 3, 1, NULL, 0, NULL, 'PV-Komponenten', '2024-11-25 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(72, 8, '2023-04-22', '20231051', '', 1, 0, 0.00, NULL, NULL, 3, 1, NULL, 0, NULL, 'Generatoranschlusskasten', '2024-11-25 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(73, 14, '2023-05-05', '20231052', '', 1, 0, 0.00, NULL, NULL, 1, 1, NULL, 0, NULL, 'Wartung Mai', '2024-11-25 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(74, 14, '2023-06-06', '20231053', '', 1, 0, 0.00, NULL, NULL, 1, 1, NULL, 0, NULL, 'Wartung Juni', '2024-11-25 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(75, 5, '2023-06-13', '20231054', '', 1, 0, 0.00, NULL, NULL, 1, 1, NULL, 0, NULL, 'Wartung PCs', '2024-11-25 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(76, 10, '2023-06-23', '20231055', '', 1, 0, 0.00, NULL, NULL, 1, 1, NULL, 0, NULL, 'PC und Telefone', '2024-11-25 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(77, 19, '2023-06-29', '20231056', '', 1, 0, 0.00, NULL, NULL, 1, 1, NULL, 0, NULL, 'PV Dorner', '2024-11-25 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(78, 10, '2023-07-04', '20231057', 'EDV Wartung', 1, 0, 0.00, NULL, NULL, 1, 1, NULL, 0, NULL, 'Wartung', '2024-11-25 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(79, 14, '2023-07-04', '20231058', '', 1, 0, 0.00, NULL, NULL, 1, 1, NULL, 0, NULL, 'Wartun Juli', '2024-11-25 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(80, 20, '2023-07-21', '20231059', '', 1, 0, 0.00, NULL, NULL, 3, 1, NULL, 0, NULL, 'AluRohre und Steckmuffen', '2024-11-25 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(81, 3, '2023-07-24', '20231060', '', 1, 0, 0.00, NULL, NULL, 1, 1, NULL, 0, NULL, 'Cafe Goldstück 1TZ', '2024-11-25 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(83, 14, '2023-07-24', '20231062', '', 1, 0, 0.00, NULL, NULL, 1, 1, NULL, 0, NULL, 'Wartung August', '2024-11-25 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(84, 14, '2023-09-13', '20231063', '', 1, 0, 0.00, NULL, NULL, 1, 1, NULL, 0, NULL, 'Wartung September', '2024-11-25 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(85, 14, '2023-10-11', '20231064', '', 1, 0, 0.00, NULL, NULL, 1, 1, NULL, 0, NULL, 'Wartung Oktober', '2024-11-25 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(86, 19, '2023-10-16', '20231065', '', 1, 0, 0.00, NULL, NULL, 3, 1, NULL, 0, NULL, 'SmartSensor', '2024-11-25 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(87, 14, '2023-10-30', '20231066', '', 1, 0, 100.00, NULL, NULL, 1, 1, NULL, 0, NULL, 'Wartung November', '2024-11-25 00:00:00', '', '2024-11-24 23:00:00', '2024-11-25 12:14:35'),
(88, 14, '2023-12-05', '20231067', '', 1, 0, 0.00, NULL, NULL, 1, 1, NULL, 0, NULL, 'Wartung Dezember', '2024-11-25 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(89, 23, '2023-12-11', '20231068', '', 1, 0, 0.00, NULL, NULL, 1, 1, NULL, 0, NULL, 'Provision Raylyst', '2024-11-25 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(90, 5, '2023-12-17', '20231069', '', 1, 0, 0.00, NULL, NULL, 1, 1, NULL, 0, NULL, 'Jahresservicepauschale', '2024-11-25 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(91, 14, '2024-01-12', '20241000', '', 1, 0, 0.00, NULL, NULL, 1, 1, NULL, 0, NULL, 'Wartung Jänner', '2024-11-25 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(92, 3, '2024-01-30', '20241001', '', 1, 0, 0.00, NULL, NULL, 1, 1, NULL, 0, NULL, 'Elektrokasten', '2024-11-25 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(93, 14, '2024-02-06', '20241002', '', 1, 0, 0.00, NULL, NULL, 1, 1, NULL, 0, NULL, 'Wartung Februar Laszlo', '2024-11-25 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(94, 3, '2024-02-24', '20241003', '', 1, 0, 1434.00, NULL, NULL, 1, 1, NULL, 0, NULL, 'Schlussrechnung Goldstück', '2024-11-25 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(95, 14, '2024-03-05', '20241004', '', 1, 0, 0.00, NULL, NULL, 1, 1, NULL, 0, NULL, 'Wartung März', '2024-11-25 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(96, 3, '2024-04-01', '20241005', '', 1, 0, 0.00, NULL, NULL, 1, 1, NULL, 0, NULL, 'Zusatztätigkeiten Goldstück', '2024-11-25 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(97, 14, '2024-04-17', '20241006', '', 1, 0, 0.00, NULL, NULL, 1, 1, NULL, 0, NULL, 'Wartung April', '2024-11-25 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(98, 24, '2024-04-17', '20241007', '', 1, 0, 0.00, NULL, NULL, 1, 1, NULL, 0, NULL, 'Goldstück', '2024-11-25 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(99, 14, '2024-05-03', '20241008', '', 1, 0, 0.00, NULL, NULL, 1, 1, NULL, 0, NULL, 'Wartung Mai', '2024-11-25 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(100, 25, '2024-05-06', '20241009', '', 1, 0, 0.00, NULL, NULL, 3, 1, NULL, 0, NULL, 'PV Rus Material', '2024-11-25 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(101, 14, '2024-06-16', '20241010', '', 1, 0, 0.00, NULL, NULL, 1, 1, NULL, 0, NULL, 'Wartung Juni', '2024-11-25 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(102, 25, '2024-06-16', '20241011', '', 1, 0, 0.00, NULL, NULL, 3, 1, NULL, 0, NULL, 'PV Montageteile', '2024-11-25 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(103, 14, '2024-07-05', '20241012', '', 1, 0, 0.00, NULL, NULL, 1, 1, NULL, 0, NULL, 'Wartung Juli', '2024-11-25 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(104, 14, '2024-08-21', '20241013', '', 1, 0, 0.00, NULL, NULL, 4, 1, NULL, 0, NULL, 'Wartung August', '2024-11-25 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(105, 14, '2024-09-11', '20241014', '', 1, 0, 0.00, NULL, NULL, 4, 1, NULL, 0, NULL, 'Wartung September', '2024-11-25 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(106, 10, '2024-09-11', '20241015', 'EDV Wartung', 1, 0, 0.00, NULL, NULL, 1, 1, NULL, 0, NULL, 'Erweiterung und Wartung', '2024-11-25 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(107, 25, '2024-09-12', '20241016', '', 1, 0, 0.00, NULL, NULL, 3, 1, NULL, 0, NULL, 'WR', '2024-11-25 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(108, 10, '2024-10-03', '20241017', 'EDV Wartung', 1, 0, 0.00, NULL, NULL, 2, 1, NULL, 0, NULL, 'Wartung', '2024-11-25 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(109, 14, '2024-10-03', '20241018', '', 1, 0, 100.00, NULL, NULL, 1, 1, NULL, 0, NULL, 'Wartung', '2024-11-25 00:00:00', '', '2024-11-24 23:00:00', '2024-11-25 12:16:31'),
(110, 3, '2024-10-07', '20241019', '', 1, 0, 0.00, NULL, NULL, 1, 1, NULL, 0, NULL, 'Montage', '2024-11-25 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(111, 14, '2024-11-11', '20241020', '', 1, 0, 0.00, NULL, NULL, 1, 1, NULL, 0, NULL, 'Wartung', '2024-11-25 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(112, 3, '2024-11-25', '20241021', '', 1, 0, 0.00, NULL, NULL, 1, 0, NULL, 0, NULL, 'Klosterneuburg WR', '2024-11-25 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(113, 5, '2024-11-25', '20241022', '', 1, 0, 0.00, NULL, NULL, 1, 0, NULL, 0, NULL, 'UCG', '2024-11-25 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(114, 26, '2024-11-25', '20241023', '', 1, 0, NULL, NULL, NULL, 1, NULL, NULL, 0, NULL, NULL, '2024-11-25 21:24:47', NULL, '2024-11-25 19:24:47', '2024-11-25 20:48:42'),
(115, 14, '2024-11-26', '20241024', '', 1, 0, 0.00, NULL, NULL, 5, NULL, NULL, 0, NULL, NULL, '2024-11-26 14:59:39', NULL, '2024-11-26 12:59:37', '2024-11-26 12:59:37');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `messages`
--

CREATE TABLE `messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(50) DEFAULT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `objectnumber` int(11) DEFAULT NULL,
  `senddate` timestamp NULL DEFAULT current_timestamp(),
  `filename` varchar(60) DEFAULT NULL,
  `withattachment` tinyint(1) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `recipientmail` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(2, '2024_08_19_000000_create_failed_jobs_table', 1),
(3, '2024_10_12_100000_create_password_reset_tokens_table', 1),
(4, '2024_10_12_100001_create_personal_access_tokens_table', 1),
(5, '2024_10_13_093315_create_salutations_table', 1),
(6, '2024_10_21_170416_create_taxrates_table', 1),
(7, '2024_10_21_170553_create_clients_table', 1),
(8, '2024_10_21_170747_create_conditions_table', 1),
(9, '2024_10_21_170748_create_roles_table', 1),
(10, '2024_10_21_170750_create_users_table', 1),
(11, '2024_10_21_170848_create_customers_table', 1),
(12, '2024_10_21_181056_create_offers_table', 1),
(13, '2024_10_24_055315_create_permissions_table', 1),
(14, '2024_10_24_061339_create_units_table', 1),
(15, '2024_10_24_061340_create_offerpositions_table', 1),
(16, '2024_11_07_180044_create_cashflows_table', 1),
(17, '2024_11_07_180501_create_mesages_table', 1),
(18, '2024_11_07_181157_create_factorrules_table', 1),
(19, '2024_11_07_181603_create_fileuploads_table', 1),
(20, '2024_11_07_181713_create_invoices_table', 1),
(21, '2024_11_07_181718_create_invoicepositions_table', 1),
(22, '2024_11_17_140738_role_permission', 1),
(23, '2024_11_17_140844_user_role', 1),
(24, '2024_11_28_083536_outgoingemails', 2);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `offerpositions`
--

CREATE TABLE `offerpositions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `offer_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(8,2) NOT NULL,
  `designation` varchar(200) DEFAULT NULL,
  `details` varchar(2000) DEFAULT NULL,
  `unit_id` bigint(20) UNSIGNED NOT NULL,
  `price` decimal(8,2) DEFAULT 0.00,
  `positiontext` tinyint(1) NOT NULL DEFAULT 0,
  `sequence` int(11) NOT NULL DEFAULT 0,
  `issoftdeleted` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `offerpositions`
--

INSERT INTO `offerpositions` (`id`, `offer_id`, `amount`, `designation`, `details`, `unit_id`, `price`, `positiontext`, `sequence`, `issoftdeleted`, `created_at`, `updated_at`) VALUES
(1, 1, 1.00, 'Bezeichnung ändern', 'Detail ändern', 1, 0.00, 0, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(2, 2, 1.00, 'Planung und Begleitung des Projekts für den Smarthomebereich', 'Unterstütung des Elektrikers:\r\n+ Unterstützung für das Verlegen der Kabeln\r\n+ Unterstützung beim Einbau der Loxonegeräte wie:\r\n       -LED Pendulum\r\n       -LED Spots\r\n       -LED RGBW Bänder\r\n       -Pure Tree Touch\r\n       -Nano Relais Tree', 4, 0.00, 0, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(3, 2, 1.00, 'Programmierung des Smarthomes nach Loxonestandard', 'Einbinden aller Geräte und Fehlersuche sowie Test auf richtige Funktion der Geräte\r\n\r\nDie Steuerung wird nach Loxone-Standard programmiert und erst nach ca. 3 Monaten angepasst; 2,5Stunden sind in diesem Angebot hierfür enthalten\r\n\r\nEibinden des bestehenden Teils in die aktuelle Programmierung und upgrade falls nötig', 4, 0.00, 0, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(4, 2, 1.00, 'Bestellung und Organisierung der Loxone Komponente', 'Rechnung erfolgt gesondert nach Ausmaß, dabei werden die Preise auf der Homepage verrechnet', 4, 0.00, 0, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(7, 2, 1.00, 'Musikserver einbinden', 'In Betriebnahme des Musikservers sowie Konfiguration', 4, 0.00, 0, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(8, 2, 1.00, 'Einzubindende Geräte', 'Wenn sich an der Liste minimal etwas ändert bleibt der Betrag gleich, wenn erheblicher Mehraufwand entsteht, wird ein gesondertes Angebot erstellt:\r\n1  	12-Kanal Verstärker\r\n2	Bewegungsmelder Baustatz inkl Löten und Rahmen\r\n10	Bewegungsmelder Tree Weiß\r\n1	Doorbird Türstation D2101V\r\n1	iPad Wallmount Silber\r\n5	KNX Präsensmelder Steinel\r\n3	LED Aufbauspot RGBW Tree\r\n4	LED Ceiling Light RGBW Air Weiß\r\n18	LED Spot RGBW Tree Weiß\r\n5	Leistungsrelais\r\n1	Music Server 8 Zonen\r\n29	Nano 2 Relay Tree\r\n7	Nano DI Tree\r\n6	Nano Dimmer Air\r\n3	Netzteil 24V, 10A\r\n1	NFC Code Touch Air Weiß\r\n10	Rauchmelder Air\r\n8	RGBW 24V Dimmer Tree\r\n6	RGBW LED Streifen 5m IP20 (berührungssicher)\r\n2	RGBW LED Streifen 5m IP68 (wasserfest)\r\n7	Speaker\r\n7	Speaker Einbaubox für abgehängte Decken\r\n6	Stellantrieb Tree\r\n2	Touch Nightlight Air\r\n3	Touch Pure Air Weiß\r\n12	Touch Pure Tree Weiß\r\n2	Touch Surface Tree\r\n16	Tür- & Fensterkontakt Air Weiß\r\n1	Wall Speaker\r\n1	Wetterstation Tree', 4, 0.00, 0, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(9, 2, 1.00, 'Netzwerk', 'Patchen aller vorhandenen Netzwerkkabeln im Patchpanel im Medienrack sowie in den Netzwerksteckdosen in den Stockwerken.\r\nPatchpanel kann von mir Zugekauft werden, ist aber nicht Bestandteil des Angebots, ebenfalls die Steckdosen. Diese müssen zu den übrigen Steckdosen dazupassen.', 4, 0.00, 0, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(11, 3, 1.00, 'Planung und Begleitung des Projekts für den Smarthomebereich', 'Unterstütung des Elektrikers:\r\n+ Unterstützung für das Verlegen der Kabeln\r\n+ Unterstützung beim Einbau der Loxonegeräte wie:\r\n       -LED Pendulum\r\n       -LED Spots\r\n       -LED RGBW Bänder\r\n       -Pure Tree Touch\r\n       -Nano Relais Tree', 4, 0.00, 0, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(12, 3, 1.00, 'Programmierung des Smarthomes nach Loxonestandard', 'Einbinden aller Geräte und Fehlersuche sowie Test auf richtige Funktion der Geräte\r\n\r\nDie Steuerung wird nach Loxone-Standard programmiert und erst nach ca. 3 Monaten angepasst; 2,5Stunden sind in diesem Angebot hierfür enthalten', 4, 0.00, 0, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(13, 3, 1.00, 'Bestellung und Organisierung der Loxone Komponente', 'Rechnung erfolgt gesondert nach Ausmaß, dabei werden die Preise auf der Homepage verrechnet', 4, 0.00, 0, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(14, 3, 1.00, 'Erweiterung der bestehenden Loxone Anlage', '+Erweiterung der Bestehenden Anlage und Verdrahtung folgender Geräte:\r\n       -3 oder 3 Stück Transformatoren à 10A je nach benötigte Leistung\r\n       -Tree Extension\r\n+Verkabelung der Stränge zum übrigen Haus, mittels Klemmen und\r\n+Absicherung der Kabeln mittels Sicherungen\r\n+Erweiterung der bestehenden Anlage um Sicherungen um ein \r\n  Durchbrennen der Kabeln zu verhindern (24V Kabeln)\r\n\r\nKasten im unteren Teil des bestehenden Platzes wird vom Elektriker bereitgestellt\r\n\r\nKlemmenmaterial und Sicherungsmaterial sowie Vergabelung innerhalb des Kastens wird von mir bereitgestellt und verdrahtet', 4, 0.00, 0, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(15, 3, 1.00, 'Musikserver einbinden', 'In Betriebnahme des Musikservers sowie Konfiguration', 4, 0.00, 0, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(16, 3, 1.00, 'Einzubindende Geräte', 'Wenn sich an der Liste minimal etwas ändert bleibt der Betrag gleich, wenn erheblicher Mehraufwand entsteht, wird ein gesondertes Angebot erstellt:\r\n	12-Kanal Verstärker\r\n5	Bewegungsmelder KNX\r\n13	Bewegungsmelder Tree Weiß\r\n1	Extension\r\n1	Intercom EU\r\n1	Intercom Unterputz-Box\r\n1 	iPAD Wall Mount \r\n3 	LED Aufbauspot RGBW\r\n4 	LED Ceiling Light RGBW Tree weiß\r\n2 	LED Pendulum Slim anthrazit\r\n10 	LED Spot RGBW Tree\r\n2 	LED Streifen Warmweiß IP20\r\n4 	LED Streifen Warmweiß IP20 (berührungssicher)\r\n3 	LED Streifen Warmweiß IP68 (wasserfest)\r\n1 	Music Server 8 Zonen\r\n3 	Nano Dimmer Air\r\n2 1	Nano Relais\r\n1 	Netzteil 24V, 0,4A\r\n3 	Netzteil 24V, 10A\r\n3 	NFC Code Touch Tree Weiß\r\n9 	Rauchmelder Air\r\n9 	RGBW Dimmer Tree\r\n7 	Speaker\r\n7 	Speaker Einbaubox für abgehängte Decken\r\n1 	Stellantrieb Tree\r\n2 	Steuerrelais\r\n2 	Touch Night Light\r\n17 	Touch pure Tree Weiß\r\n2 	Touch Surface Tree\r\n1 	Tree Extension\r\n14 	Tür- & Fensterkontakt Air Weiß\r\n2 	Wall Speaker\r\n1 	Wetterstation Tree', 4, 0.00, 0, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(17, 3, 1.00, 'Leistungsberechnung:', 'Ganzen Tätigkeiten die in diesem Angebot angeführt wurden, werden in dieser Position zusammengefasst und mit diesem Betrag angeboten', 2, 3660.00, 0, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(20, 4, 0.00, '', 'Folgende Geräte werden für das Projekt Neues-Dorf 34 benötigt:', 3, 0.00, 0, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(38, 4, 0.00, '', 'Gartenbewässerung und Poolsteuerung; Geräte werden zu einem spätern Zeitpunkt angeboten, da jetzt nicht feststeht was benötigt wird', 3, 0.00, 1, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(40, 4, 1.00, 'Bestellvorgang und Zahlung', 'Unser Vorschlag wäre die Bestellung auf mehrere Etapen zu teilen um auch die Zahlung auf mehrere Etapen verteilen. \r\n\r\nAls erstes würden wir die Relais und RGBW Dimmer und die 5-Punkt-Taster bestellen, danach die Lichter, und zum Schluss den Musik-Server und die restlichen Geräte.\r\n\r\nNatürlich können wir auch alle Geräte auf einmal bestellen. Wie eben vom Kunden gewünscht.\r\n\r\nDa von unserer Seite nicht anders möglich würden wir auf Vorauskasse bestehen.', 4, 0.00, 0, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(43, 5, 1.00, 'Programmierung SPS', 'Projekt Kastaniengasse', 2, 1800.00, 0, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(48, 10, 0.00, '', 'Angebot bezieht sich auf das gelistete Zubehör sowie Verkabelung des Steuerkastens und Programmierung', 3, 0.00, 1, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(50, 10, 1.00, 'Erklärung', '-Die Positionen beinhalten:\r\n-Ceiling Light: Lichter im WC, Bad und Vorraum\r\n-Dimmer für 4 dimmbare Lichter in Wohnbereich und/oder                    \r\n      Schlafzimmer\r\n-RGBW-Streifen für die Wohnküche inkl Steuerung\r\n-Touch-Taster für die verschiedenen Räume\r\n-NFC Code touch: für die Bedienung auf der Aussenseite Eingangstür\r\n-Wetterstation für Sicherheit der Jalousien\r\n-Verkabelte Steuerung der Jalousien\r\n-Präsensmelder für die Steuerung des Lichts und/oder Alarm\r\n-Alarmsirene\r\n-Rauchmelder\r\n\r\nAngebot beinhaltet verkabelten Steuerkasten und Anbindung an die Geräte vor Ort, sowie Programmierung mit einer Standardprogrammierung die nach Kundenwunsch angepasst werden kann. \r\n\r\nPlanung für Verkabelung  wird mit der Fa. ADG-Elektrotechnik gemacht.\r\n\r\nIm Lauf der Planung kann es sein, dass gewisse Teile wegfallen oder dazu kommen. Das ist ein erster Rohentwurf', 1, 0.00, 0, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(51, 11, 1.00, 'Smarthome Steuerung für Ordination', '-4 Ausgänge für dimmbare Lichter für alle 4 Ordinationsräume (Lichter müssen dimmbar sein!!!)\r\n-10 Ausgänge für normale Lichter\r\n-8 Tree Touch: für die Räume in denen Jalousien und/oder Lautsprecher verbaut sind\r\n-18 Ausgänge für das Steuerun der 9 Jalousien\r\n-14 Präsensmelder (für Lichtsteuerung und/oder Alarm)\r\n-9 Türkontakte (für Alarm)\r\n-14 Rauchmelder\r\n-8 Digitaleingänge (für die Steuerung der übrigen Räume und für die Notfalltaster in den Toiletten)\r\n-1 Kabelschrank für den Miniserver und den dafür vorgesehenen Komponenten\r\n\r\nDieses Anlage beinhaltet die Einbindung der Geräte und Inbetriebnahme. \r\nKabeln müssen vom Elektriker von den Dosen bis zum Schrank verlegt sein. \r\nIn diesem Angebot sind keine Verbindungskabeln enthalten!!!\r\nAuch beinhaltet dieses Angebot keine Leuchtmittel!', 2, 11500.00, 0, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(54, 11, 1.00, 'Telefonanlage Kapsch für ISDN', 'Kommunikationsserver SMBC\r\n  -8 Nebenstellen\r\n  -6 analoge Nebenstellen\r\n5Stk Systemapparaete Standard\r\n\r\n\r\nServices inkludiert:\r\n    - Basic Voice Mail\r\n    -Text vor dem Melden, Musik im Wartezustand, Verschiedene \r\n      Ansagen\r\n\r\nInbetriebnahme durch Kapsch inkludiert!\r\n\r\nOptional:\r\n  -1Stk Telefoninterface für Durchsagen in Warteraum -->89€ exlk. St\r\n  -1Stk Schnurlostelefon DECT Siemens -->99€ exkl St.', 2, 3640.00, 0, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(55, 12, 1.00, 'Telefonanlage', 'Kommunikationsserver SMBC\r\n  -8 Nebenstellen\r\n  -6 analoge Nebenstellen\r\n5Stk Systemapparaete Standard\r\n\r\n\r\nServices inkludiert:\r\n    - Basic Voice Mail\r\n    -Text vor dem Melden, Musik im Wartezustand, Verschiedene \r\n      Ansagen\r\n\r\nInbetriebnahme durch Kapsch inkludiert!\r\n\r\nOptional:\r\n  -1Stk Telefoninterface für Durchsagen in Warteraum -->89€ exlk. St\r\n  -1Stk Schnurlostelefon DECT Siemens -->99€ exkl St.', 1, 3640.00, 0, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(56, 13, 1.00, 'Bewegungsmelder Toreingang', 'Bewegungsmelder Aussenbereich\r\nhttps://shop.loxone.com/deat/bewegungsmelder-24v.html\r\ninkl. Einbindung ins Smarthome\r\nd.h.: Einbindung in die Sensoreingänge des Miniservers und Programmierung \r\nMontage von Relais für das Schalten der Lichter am Gang\r\nEinbindung der Lichter in die Anlage\r\ninkl. Klemmen und Zusatzmaterial für den Kasten', 1, 451.00, 0, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(59, 14, 1.00, 'Vorbereitungstätigkeiten', '# Komponentensuche und Bestellung mit Hr. Rus\r\n\r\n# Einrichtung der PCs aus der Ordination zuhause\r\n\r\n# Klärung des A1 Internetanschlusses \r\n  - Vorabklärung um etwaige Leitungsbauarbeiten im Vorhinhein\r\n     zu planen\r\n  - Klärung der möglichen Geschwindigkeit für einen neuen \r\n     Internetanschluss \r\n\r\n# Vorbereitung des Servers: \r\n  -Server RAM und PowerSupply einbauen\r\n  -Einrichtung ILO\r\n  -Betriebssystem installieren und einrichten\r\n  -Installation der Rollen (Active Directory, Fileshare,  DFS, \r\n    Remotedesktop)\r\n  -Einrichtung des Speicherplatzes und Rechte festlegen\r\n  -Installation Backupprogramm und Einrichtung\r\n  -Installation Office am Server', 2, 1615.00, 0, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(60, 14, 1.00, 'Tätigkeiten in der Ordination vor Öffnung', '#Netzwerk \r\n  - Festlegen der IP-Adressbreieche und Fixierung\r\n  - SecurityGateway einrichten \r\n  - Netzwerke einrichten und IP-Bereich festlegen\r\n  - Routing einrichten \r\n\r\n# Domäne und Benutzer\r\n  - Übernahme der bestehenden PCs in die Domäne\r\n  - Einrichtung der Benutzer und zuweisung der Ordnerrechte\r\n\r\n# Adminsitration der bestehenden Programme\r\n  - Verschieben der Programmdatenbanken auf dem Server\r\n\r\n#Backup konfigurieren mit rotierenden Einsatz der Festplatten\r\n\r\n# Feintuning Telefonanlage\r\n\r\nDafür werden 4 Tage reserviert, vorzugsweise an Wochenenden', 2, 2560.00, 0, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(61, 14, 0.00, '', 'Zu klären ein etwaiger Servicevertrag oder Abrechnung der Einsatzstunden nach Vereinbarung', 3, 0.00, 1, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(63, 16, 1.00, 'Telefonanlage', 'Telefonanlage mit Kommunikationsserver für Digitale Amtsleitungen ISDN Basis, inklusive folgener Geräte und Zubehör:\r\n\r\n - 1 Stk Kommunikationsserver SMBC für die Montage im Rack-Schrank\r\n      + 8 Digitale Nebenstellenanschlüsse\r\n      + 6 Analoge Nebenstellenanschlüsse\r\n      + Basic Voice Mail\r\n      + 1 Text vor Melden, Musik im Wartezustand, …\r\r\n      + Analoge Nebenstelle für bestehendes Faxgerät (1 Stk.)\r\n - 6 Digitale Endgeräte\r\n - 1 Interface für Warteraum inkl. Verstärker für Lautsprecher\r\n - nötige Verbindungskabeln im Schrank\r\n - Lizenzen für den Betrieb\r\n - Verteilertätigkeiten bzw. Verkabelung der Telefonanlage\r\n - Inbetriebnahme des Kommunikationssystems', 2, 4607.00, 0, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(64, 17, 1.00, 'Bezeichnung ändern', 'Steuerung der Heizung auf Basis des erzeugtem Strom aus der PV.\r\nBenötigt werden folgende Teile:\r\n- 	Miniserveinkl. 	Netzteil 24V\r\n- 	LTE Router (Huawei B535 weiß)\r\n- 	Koppelrelais 16A (Finder 4C.01.9.024.0050)\r\n- 	Aufputzkasten (Aufputz-Feldverteiler SKI IP40 3x12 PLE FW312W)\r\n      inkl. Klemmen und Sicherungen 16A und oder 13A (5 Stück) sowie\r\n      Kabeln  für den Verteiler\r\n- 	Modbus RTU Ethernt Converter\r\n\r\nVormontage des Kastens und erste Rohprogrammierung werden im vorhinein erledigt. \r\nAufbau vorort und Endprogrammierung im Zusammenhang mit dem Inverter inbegriffen', 2, 2283.00, 0, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(65, 18, 1.00, 'Telefonanlage', 'Telefonanlage mit Kommunikationsserver für Digitale Amtsleitungen ISDN Basis, inklusive folgener Geräte und Zubehör:\r\n\r\n - 1 Stk Kommunikationsserver SMBC für die Montage im Rack-Schrank\r\n      + 8 Digitale Nebenstellenanschlüsse\r\n      + 6 Analoge Nebenstellenanschlüsse\r\n      + Basic Voice Mail\r\n      + 1 Text vor Melden, Musik im Wartezustand, …\r\r\n      + Analoge Nebenstelle für bestehendes Faxgerät (1 Stk.)\r\n - 6 Digitale Endgeräte\r\n - 1 Interface für Warteraum inkl. Verstärker für Lautsprecher\r\n - nötige Verbindungskabeln im Schrank\r\n - Lizenzen für den Betrieb\r\n - Verteilertätigkeiten bzw. Verkabelung der Telefonanlage\r\n - Inbetriebnahme des Kommunikationssystems \r\n\r\nLieferung nach Montage der Möbel, Termin wird noch ausgemacht', 2, 0.00, 0, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(66, 18, 1.00, '1.Teilrechnung Telefonanlage', '50% Anzahlung für die Telefonanlage aus Angebot 20226015', 2, 2303.00, 0, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(67, 19, 1.00, 'Zusatztätigkeiten', '8m     Kabel 6m² schwarz\r\n2,6m 	Kabel 6m² blau\r\n2,6m 	Kabel 6m² gelb\r\n3	tk  	Sicherungen 25A\r\n100m 	Kabel 3x2,5mm²\r\n2m    	Hager Kabelkanal\r\n1	Pa  	Tätigkeit', 2, 312.00, 0, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(69, 20, 0.00, '', 'Alle Kabel (Jalousiekabel, Lichterkabel und Buskabel von den Tastern und Präsensmeldern sowie Digitale Eingänge) müssen vor der Montage in den Technikraum geführt sein.', 3, 0.00, 1, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(70, 20, 0.00, '', 'Spannungsversorgung mit 5x6mm² (Abgesichert mit 25A Leitungsschutzschalter) müssen vorbereitet sein. Die weitere Absicherung der einzelnen Kabeln wird im Loxone Kasten, mit Rücksicht auf Querschnitt, gemacht.', 3, 0.00, 1, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(71, 20, 0.00, '', 'Zahlungskonditionen:  50% Anzahlung, Rest nach Inbetriebnahme', 3, 0.00, 1, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(72, 21, 1.00, 'Server', 'HPE ML30 Generation 10 \r\n16GB RAM, 4-bay SAS Festplatten\r\n4x HPE Festplatten SAS 3,5\" à 4TB\r\nAufstockung auf 32GB RAM', 2, 2753.00, 0, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(73, 21, 1.00, 'Netzwerkkomponente', '1Stk Ubiqitiy Unifi Security Gateway\r\n1Stk Ubiquiti Unifi Switch\r\n2Stk Ubiquiti AccessPoint', 2, 881.00, 0, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(74, 21, 1.00, 'Lizenzen', '1Stk Windows Server 2019 Standard Lizenz\r\n5Stk ClientLizenzen\r\n1Stk Windows 10 Lizenz (für Virtuellen PC)\r\n1Stk Office 2019 Lizenz (für Virtuellen PC)\r\n1Stk Backup Software mit Rotation der Festplatten', 1, 1384.00, 0, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(75, 21, 1.00, 'Homepageerstellung', 'Konfiguration Webspace, Domain und E-Mail\r\nSlider (Bilderkarussell)\r\nSSL-Zertifikat konfiguration\r\nSDGVO Konformität\r\nImpressum\r\n\r\nOptional: \r\nHomepageerstellung durch einen Bekannten von mir à 3.240€', 2, 1454.00, 0, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(76, 21, 1.00, 'Servervorbereitung', 'Installation des Serverbetriebssystems \r\nEinrichtung Remote Desktop und Konfiguration\r\nUmstellen von derzeitiger Infrastruktur auf Servergesteuert\r\nVorbereitung von Laufwerken und Shares,\r\nFreigaben einrichten\r\nBenutzer einrichten\r\nSicherheitsgruppen einrichten', 2, 2532.00, 0, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(77, 21, 1.00, 'Netzwerk', 'Netzwerksicherung und Trennung zwischen Ordination und \"Privat\"\r\nKonfiguration der VPN Verbindung für den Zugriff von Ausserhalb', 2, 234.00, 0, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(78, 21, 1.00, 'Migration', 'Migration der vorhandenen Daten', 2, 385.00, 0, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(79, 21, 1.00, 'Betreuung vorort', 'Betreuung nach der Umstellung vorort für die Zeit die nötig ist\r\nLösen von Problemen die nach der Umstellung auftauchen könnten', 2, 472.00, 0, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(80, 21, 0.00, '', 'Optional:\r\n1/4 jährliche Updates der Homepage \r\n(Grundlegende Updates, Update Plugins, Update Themes, Backup)  \r\nà 90,00€ vierteljährlich (inkl. Umsatzsteuer)', 3, 0.00, 1, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(81, 21, 0.00, '', 'Optional: Wartungsvertrag Server und Clients \r\nmonatlich  à 182,00€/Monat (inkl. Umsatzsteuer)', 3, 0.00, 1, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(82, 21, 0.00, '', 'Zahlungsbedienungen: 50% Anzahlung bei Angebotserteilung; \r\nRest nach Fertigstellung der einzelnen Etapen, nach Absprache', 3, 0.00, 1, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(83, 22, 1.00, 'Bezeichnung ändern', 'Detail ändern', 1, 0.00, 0, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(86, 24, 1.00, 'Videoüberwachungsanlage Innenraum', '-6 Stk. Unifi  Überwachungskamera G3 Serie \r\n-1 Stk. Unifi Dream Machine inkl. Festplatte 2 TB für Dauerbetrieb geeignet\r\n-Montage, Netzwerkkabeln und Stecker\r\n-Configuration der Anlage\r\n\r\nZusätzliche Kosten im Wartungsvertrag ca. 5,90€/Monat, aufgrund von Update und Backuptätigkeiten der Videos', 1, 1724.00, 0, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(89, 26, 1.00, 'Bezeichnung ändern', 'Detail ändern', 1, 0.00, 0, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(90, 27, 1.00, 'Planung und Begleitung des Projekts für den Smarthomebereich', 'Unterstütung des Elektrikers:\r\n+ Unterstützung für das Verlegen der Kabeln\r\n+ Unterstützung beim Einbau der Loxonegeräte wie:\r\n       -LED Pendulum\r\n       -LED Spots\r\n       -LED RGBW Bänder\r\n       -Pure Tree Touch\r\n       -Nano Relais Tree', 4, 0.00, 0, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(91, 27, 1.00, 'Programmierung des Smarthomes nach Loxonestandard', 'Einbinden aller Geräte und Fehlersuche sowie Test auf richtige Funktion der Geräte\r\n\r\nDie Steuerung wird nach Loxone-Standard programmiert und erst nach ca. 3 Monaten angepasst; 2,5Stunden sind in diesem Angebot hierfür enthalten\r\n\r\nEibinden des bestehenden Teils in die aktuelle Programmierung und upgrade falls nötig', 4, 0.00, 0, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(92, 27, 1.00, 'Bestellung und Organisierung der Loxone Komponente', 'Rechnung erfolgt gesondert nach Ausmaß, dabei werden die Preise auf der Homepage verrechnet', 4, 0.00, 0, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(94, 27, 1.00, 'Musikserver einbinden', 'In Betriebnahme des Musikservers sowie Konfiguration', 4, 0.00, 0, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(95, 27, 1.00, 'Einzubindende Geräte', 'Wenn sich an der Liste minimal etwas ändert bleibt der Betrag gleich, wenn erheblicher Mehraufwand entsteht, wird ein gesondertes Angebot erstellt:\r\n1  	12-Kanal Verstärker\r\n2	Bewegungsmelder Baustatz inkl Löten und Rahmen\r\n10	Bewegungsmelder Tree Weiß\r\n1	Doorbird Türstation D2101V\r\n1	iPad Wallmount Silber\r\n5	KNX Präsensmelder Steinel\r\n3	LED Aufbauspot RGBW Tree\r\n4	LED Ceiling Light RGBW Air Weiß\r\n18	LED Spot RGBW Tree Weiß\r\n5	Leistungsrelais\r\n1	Music Server 8 Zonen\r\n29	Nano 2 Relay Tree\r\n7	Nano DI Tree\r\n6	Nano Dimmer Air\r\n3	Netzteil 24V, 10A\r\n1	NFC Code Touch Air Weiß\r\n10	Rauchmelder Air\r\n8	RGBW 24V Dimmer Tree\r\n6	RGBW LED Streifen 5m IP20 (berührungssicher)\r\n2	RGBW LED Streifen 5m IP68 (wasserfest)\r\n7	Speaker\r\n7	Speaker Einbaubox für abgehängte Decken\r\n6	Stellantrieb Tree\r\n2	Touch Nightlight Air\r\n3	Touch Pure Air Weiß\r\n12	Touch Pure Tree Weiß\r\n2	Touch Surface Tree\r\n16	Tür- & Fensterkontakt Air Weiß\r\n1	Wall Speaker\r\n1	Wetterstation Tree', 4, 0.00, 0, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(96, 27, 1.00, 'Netzwerk', 'Patchen aller vorhandenen Netzwerkkabeln im Patchpanel im Medienrack sowie in den Netzwerksteckdosen in den Stockwerken.\r\nPatchpanel kann von mir Zugekauft werden, ist aber nicht Bestandteil des Angebots, ebenfalls die Steckdosen. Diese müssen zu den übrigen Steckdosen dazupassen.', 4, 0.00, 0, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(98, 28, 1.00, 'Planung und Begleitung des Projekts für den Smarthomebereich', 'Unterstütung des Elektrikers:\r\n+ Unterstützung für das Verlegen der Kabeln\r\n+ Unterstützung beim Einbau der Loxonegeräte wie:\r\n       -LED Pendulum\r\n       -LED Spots\r\n       -LED RGBW Bänder\r\n       -Pure Tree Touch\r\n       -Nano Relais Tree', 4, 0.00, 0, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(99, 28, 1.00, 'Programmierung des Smarthomes nach Loxonestandard', 'Einbinden aller Geräte und Fehlersuche sowie Test auf richtige Funktion der Geräte\r\n\r\nDie Steuerung wird nach Loxone-Standard programmiert und erst nach ca. 3 Monaten angepasst; 2,5Stunden sind in diesem Angebot hierfür enthalten\r\n\r\nEibinden des bestehenden Teils in die aktuelle Programmierung und upgrade falls nötig', 4, 0.00, 0, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(100, 28, 1.00, 'Bestellung und Organisierung der Loxone Komponente', 'Rechnung erfolgt gesondert nach Ausmaß, dabei werden die Preise auf der Homepage verrechnet', 4, 0.00, 0, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(102, 28, 1.00, 'Musikserver einbinden', 'In Betriebnahme des Musikservers sowie Konfiguration', 4, 0.00, 0, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(103, 28, 1.00, 'Einzubindende Geräte', 'Wenn sich an der Liste minimal etwas ändert bleibt der Betrag gleich, wenn erheblicher Mehraufwand entsteht, wird ein gesondertes Angebot erstellt:\r\n1  	12-Kanal Verstärker\r\n2	Bewegungsmelder Baustatz inkl Löten und Rahmen\r\n10	Bewegungsmelder Tree Weiß\r\n1	Doorbird Türstation D2101V\r\n1	iPad Wallmount Silber\r\n5	KNX Präsensmelder Steinel\r\n3	LED Aufbauspot RGBW Tree\r\n4	LED Ceiling Light RGBW Air Weiß\r\n18	LED Spot RGBW Tree Weiß\r\n5	Leistungsrelais\r\n1	Music Server 8 Zonen\r\n29	Nano 2 Relay Tree\r\n7	Nano DI Tree\r\n6	Nano Dimmer Air\r\n3	Netzteil 24V, 10A\r\n1	NFC Code Touch Air Weiß\r\n10	Rauchmelder Air\r\n8	RGBW 24V Dimmer Tree\r\n6	RGBW LED Streifen 5m IP20 (berührungssicher)\r\n2	RGBW LED Streifen 5m IP68 (wasserfest)\r\n7	Speaker\r\n7	Speaker Einbaubox für abgehängte Decken\r\n6	Stellantrieb Tree\r\n2	Touch Nightlight Air\r\n3	Touch Pure Air Weiß\r\n12	Touch Pure Tree Weiß\r\n2	Touch Surface Tree\r\n16	Tür- & Fensterkontakt Air Weiß\r\n1	Wall Speaker\r\n1	Wetterstation Tree', 4, 0.00, 0, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(104, 28, 1.00, 'Netzwerk', 'Patchen aller vorhandenen Netzwerkkabeln im Patchpanel im Medienrack sowie in den Netzwerksteckdosen in den Stockwerken.\r\nPatchpanel kann von mir Zugekauft werden, ist aber nicht Bestandteil des Angebots, ebenfalls die Steckdosen. Diese müssen zu den übrigen Steckdosen dazupassen.', 4, 0.00, 0, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(105, 28, 1.00, 'Leistungsberechnung', 'Ganzen Tätigkeiten die in diesem Angebot angeführt wurden, werden in dieser Position zusammengefasst und mit diesem Betrag angeboten', 1, 4558.00, 0, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(106, 29, 1.00, 'Wartungspauschale', 'Wartungspauschale aus Vertrag für März 2023', 2, 212.00, 0, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(107, 30, 1.00, 'Wartung 2.Quartal 2023', '', 2, 465.00, 0, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(108, 31, 1.00, 'Bezeichnung ändern', 'Detail ändern', 1, 0.00, 0, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(109, 32, 1.00, 'Wartungspauschale', 'Wartungspauschale aus Vertrag für April 2024', 2, 223.00, 0, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(110, 33, 1224.00, 'TRINA SOLAR BLACK FRAME', 'TSM-NEG9R.28-435 | 435W\r\nRTD_TSM-NEG9R.28435BF', 1, 82.00, 0, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(111, 33, 50.00, 'HUAWEI SUN2000-10KTL-M1 (HC)', '3Phase\r\n10kW', 1, 1482.00, 0, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(112, 33, 50.00, 'HUAWEI LUNA2000-5-C0   BMS', 'Leistungsmodul', 1, 787.00, 0, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(113, 33, 100.00, 'HUAWEI LUNA2000-5-E0  5kWh', 'Batteriemodul 5kWh', 1, 1813.00, 0, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(114, 33, 50.00, 'Huawei DTSU666-H 100A (3phase)', 'Leistungsmessgerät DTSU666-H', 1, 125.00, 0, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(116, 33, 50.00, 'Photovoltaik Unterkonstruktion für 6x4 Module', 'Realisiert mit dem K2 System\r\nhttps://catalogue.k2-systems.com/de\r\n\r\nSingleHook 3S --> 2000Stk\r\nWood screw 8x100 --> 4000Stk\r\nOneEnd Set 30-42 --> 800Stk\r\nOneMid Set 30-42 --> 2000Stk\r\nSingleRail 36 End Cap --> 800Stk\r\nK2 Solar Cable Manager --> 1200Stk\r\nSingleRail 36; 4.40 m --> 800Stk\r\nSingleRail 36 RailConnector Set --> 400Stk', 1, 1003.00, 0, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(117, 33, 500.00, 'WM PV Stick PUSH-IN', 'Steckverbinder Buchse', 1, 4.00, 0, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(118, 33, 500.00, 'WM PV-Stick PUSH-IN', 'Steckverbinder Stift', 1, 4.00, 0, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(119, 33, 50.00, 'Solarkabel Erdverlegbar', '100m schwarz\r\nH1Z2Z2-K', 1, 42.00, 0, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(120, 33, 50.00, 'Solarkabel Erdverlegbar', '100m rot\r\nH1Z2Z2-K', 1, 42.00, 0, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(121, 33, 0.00, '', 'Angebot nur gültig bei Gesamtabnahmenverpflichtung!\r\nDieses Angebot beinhaltet keienerlei Transportkosten! Diese müssen bei Feststehen der Lieferorte gesondert berechnet werden.\r\nFinanzierung durch Vorkasse!', 3, 0.00, 1, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(122, 33, 50.00, 'WM Generatoranschlusskasten 1100V', 'PVNDC2IN/1OUTX2SPD2R 2MPPT WM4C Typ 2', 1, 225.00, 0, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(123, 33, 0.00, '', 'Über einzelne Projekte die daraus abgeleitet werden sollten und der Projektabwicklung, muss das Angebot neu bewertet werden!', 3, 0.00, 1, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(124, 34, 1.00, 'Wartungspauschale', 'Wartungspauschale aus Vertrag für Mai 2024', 2, 223.00, 0, 0, 0, '2024-11-24 23:00:00', '2024-11-24 23:00:00');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `offers`
--

CREATE TABLE `offers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp(),
  `number` int(11) NOT NULL,
  `description` varchar(200) DEFAULT NULL,
  `tax_id` bigint(20) UNSIGNED NOT NULL,
  `taxburden` double DEFAULT NULL,
  `condition_id` bigint(20) UNSIGNED NOT NULL,
  `archived` tinyint(1) NOT NULL DEFAULT 0,
  `archiveddate` datetime DEFAULT NULL,
  `comment` varchar(200) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `offers`
--

INSERT INTO `offers` (`id`, `customer_id`, `date`, `number`, `description`, `tax_id`, `taxburden`, `condition_id`, `archived`, `archiveddate`, `comment`, `created_at`, `updated_at`) VALUES
(1, 1, '2019-10-17', 20196000, '', 1, 0, 1, 0, '1990-01-01 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(2, 2, '2019-10-17', 20196001, 'Projekt Thomas Huber', 2, 0, 1, 1, '2019-10-17 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(3, 2, '2019-10-23', 20196002, 'Projekt Thomas Huber', 2, 0, 1, 1, '2019-10-23 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(4, 2, '2019-11-05', 20196003, 'Projekt Neues Dorf 34, Groß-Enzersdorf', 2, 0, 1, 1, '2019-11-05 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(5, 3, '2019-11-28', 20196004, '', 2, 0, 1, 1, '2019-11-28 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(7, 2, '2020-01-31', 20206006, 'Aufputz- bzw. Hohlraumkästen für Projekt Rutzendorf', 2, 0, 1, 1, '2020-01-31 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(9, 3, '2020-09-03', 20206008, 'Baustelle Baumeister', 2, 0, 1, 1, '2020-09-03 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(10, 3, '2021-02-21', 20216009, '', 2, 0, 1, 1, '2021-02-21 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(11, 3, '2021-08-29', 20216010, '', 2, 0, 1, 1, '2021-08-29 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(12, 3, '2021-09-03', 20216011, '', 1, 0, 1, 1, '2021-09-03 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(13, 6, '2021-10-20', 20216012, '', 2, 0, 1, 1, '2021-10-20 00:00:00', 'Bewegungsmelder 	135,82€\r\nTransport 	96€\r\nEinbau in Anlage 	100€\r\nRelais 2Stk 	4€\r\nEinbau Lichter Aussen 	6€\r\nKleinmaterial 	20€\r\n Zusammen: 	451,828€', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(14, 10, '2021-12-15', 20216013, 'Server und Programmierung', 2, 0, 1, 1, '2021-12-15 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(15, 10, '2021-12-15', 20216014, 'Netzwerk', 2, 0, 1, 1, '2021-12-15 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(16, 10, '2022-01-13', 20226015, 'Telefonanlage', 2, 0, 1, 1, '2022-01-13 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(17, 12, '2022-06-10', 20226016, '', 2, 0, 1, 1, '2022-06-10 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(18, 10, '2022-06-30', 20226017, 'Telefonanlage 1.Teilrechnung', 2, 0, 1, 1, '2022-06-30 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(19, 12, '2022-08-27', 20226018, '', 2, 0, 1, 1, '2022-08-27 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(20, 13, '2022-08-29', 20226019, 'Einfamilienhaus Loxone Aufbau', 2, 0, 1, 1, '2022-08-29 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(21, 14, '2022-09-01', 20226020, '', 2, 0, 1, 0, '1990-01-01 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(22, 10, '2022-12-15', 20226021, 'Wartungsvertrag', 1, 0, 1, 0, '1990-01-01 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(23, 14, '2023-01-11', 20236022, '', 2, 0, 1, 0, '1990-01-01 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(24, 10, '2023-02-13', 20236023, '', 2, 0, 1, 0, '1990-01-01 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(25, 17, '2023-02-19', 20236024, 'Umbau von reiner KNX auf Loxone mit KNX Steuerung', 2, 0, 1, 0, '1990-01-01 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(26, 10, '2023-03-12', 20236025, 'Wartungsvertrag', 1, 0, 1, 0, '1990-01-01 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(27, 2, '2023-03-30', 20231026, 'Projekt Thomas Huber', 2, 0, 1, 0, '1990-01-01 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(28, 2, '2023-03-30', 20231027, 'Projekt Thomas Huber', 2, 0, 1, 0, '1990-01-01 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(29, 14, '2023-04-04', 20236028, '', 2, 0, 1, 0, '1990-01-01 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(30, 10, '2023-04-04', 20236029, 'EDV Wartung', 2, 0, 1, 0, '1990-01-01 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(31, 1, '2023-04-16', 20236030, '', 1, 0, 1, 0, '1990-01-01 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(32, 14, '2023-10-30', 20236031, '', 2, 0, 1, 0, '1990-01-01 00:00:00', '', '2024-11-24 23:00:00', '2024-11-24 23:00:00'),
(33, 22, '2023-11-16', 20236032, 'Entwurfsangebot für 50x PV-Anlnagen zu je 10kWp', 2, 0, 1, 0, '1990-01-01 00:00:00', NULL, '2024-11-24 23:00:00', '2024-11-25 10:47:22'),
(34, 14, '2024-05-03', 20246033, '', 1, 0, 1, 0, '1990-01-01 00:00:00', NULL, '2024-11-24 23:00:00', '2024-11-25 11:02:32');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `outgoingemails`
--

CREATE TABLE `outgoingemails` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` int(11) NOT NULL COMMENT 'E-Mail-Typ (z. B. 1 = Rechnung, 2 = Angebot)',
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `getteremail` varchar(100) NOT NULL,
  `objectnumber` int(11) NOT NULL COMMENT 'Referenznummer des zugehörigen Objekts',
  `sentdate` datetime NOT NULL COMMENT 'Datum, an dem die E-Mail gesendet wurde',
  `filename` varchar(255) NOT NULL COMMENT 'Name der angehängten Datei oder des E-Mail-Protokolls',
  `withattachment` tinyint(1) NOT NULL COMMENT 'Gibt an, ob ein Anhang vorhanden ist (true/false)',
  `status` tinyint(1) NOT NULL COMMENT 'Status der E-Mail (z. B. 0 = fehlgeschlagen, 1 = gesendet)',
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `outgoingemails`
--

INSERT INTO `outgoingemails` (`id`, `type`, `customer_id`, `getteremail`, `objectnumber`, `sentdate`, `filename`, `withattachment`, `status`, `client_id`, `created_at`, `updated_at`) VALUES
(2, 1, 3, 'office@bulz.at', 112, '2024-11-28 00:00:00', 'Rechnung.pdf', 1, 1, 1, '2024-11-28 07:55:26', '2024-11-28 07:55:26'),
(3, 1, 3, '', 112, '2024-11-28 00:00:00', 'Rechnung.pdf', 1, 0, 1, '2024-11-28 08:00:52', '2024-11-28 08:00:52'),
(4, 1, 3, '', 112, '2024-11-28 00:00:00', 'Rechnung.pdf', 1, 1, 1, '2024-11-28 08:01:46', '2024-11-28 08:01:46'),
(5, 1, 3, '', 112, '2024-11-28 00:00:00', 'Rechnung.pdf', 1, 1, 1, '2024-11-28 08:03:04', '2024-11-28 08:03:04'),
(6, 1, 3, '', 112, '2024-11-28 00:00:00', 'Rechnung.pdf', 1, 1, 1, '2024-11-28 08:03:22', '2024-11-28 08:03:22'),
(7, 1, 3, '', 112, '2024-11-28 00:00:00', 'Rechnung.pdf', 1, 1, 1, '2024-11-28 08:05:01', '2024-11-28 08:05:01'),
(8, 1, 3, '', 112, '2024-11-28 00:00:00', 'Rechnung.pdf', 1, 1, 1, '2024-11-28 08:05:21', '2024-11-28 08:05:21'),
(9, 1, 3, '', 112, '2024-11-28 00:00:00', 'Rechnung.pdf', 1, 1, 1, '2024-11-28 08:06:00', '2024-11-28 08:06:00'),
(10, 1, 3, '', 112, '2024-11-28 00:00:00', 'Rechnung.pdf', 1, 1, 1, '2024-11-28 08:06:29', '2024-11-28 08:06:29'),
(11, 1, 3, '', 112, '2024-11-28 00:00:00', 'Rechnung.pdf', 1, 1, 1, '2024-11-28 08:08:29', '2024-11-28 08:08:29'),
(12, 1, 3, '', 112, '2024-11-28 00:00:00', 'Rechnung.pdf', 1, 1, 1, '2024-11-28 08:08:53', '2024-11-28 08:08:53'),
(13, 1, 3, '', 112, '2024-11-28 00:00:00', 'Rechnung.pdf', 1, 1, 1, '2024-11-28 08:09:35', '2024-11-28 08:09:35'),
(14, 1, 3, '', 112, '2024-11-28 00:00:00', 'Rechnung.pdf', 1, 1, 1, '2024-11-28 08:10:52', '2024-11-28 08:10:52'),
(15, 1, 14, 'office@bulz.at', 20241024, '2024-11-28 00:00:00', 'Rechnung.pdf', 1, 1, 1, '2024-11-28 08:30:30', '2024-11-28 08:30:30'),
(16, 1, 14, 'lucian@bulz.at', 20241024, '2024-11-28 00:00:00', 'Rechnung.pdf', 1, 0, 1, '2024-11-28 08:50:03', '2024-11-28 08:50:03'),
(17, 1, 3, 'office@bulz.at', 20241021, '2024-11-28 09:52:40', 'Rechnung.pdf', 1, 0, 1, '2024-11-28 08:52:40', '2024-11-28 08:52:40'),
(18, 1, 14, 'office@bulz.at', 20241024, '2024-11-28 09:55:23', 'Rechnung.pdf', 1, 1, 1, '2024-11-28 08:55:25', '2024-11-28 08:55:25');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'view_dashboard', 'Erlaubt Zugriff auf das Dashboard', '2024-11-25 10:31:37', '2024-11-25 10:31:37'),
(2, 'edit_posts', 'Erlaubt das Bearbeiten von Beiträgen', '2024-11-25 10:31:37', '2024-11-25 10:31:37'),
(3, 'delete_posts', 'Erlaubt das Löschen von Beiträgen', '2024-11-25 10:31:37', '2024-11-25 10:31:37'),
(4, 'create_users', 'Erlaubt das Erstellen neuer Benutzer', '2024-11-25 10:31:37', '2024-11-25 10:31:37'),
(5, 'edit_users', 'Erlaubt das Bearbeiten von Benutzerdaten', '2024-11-25 10:31:37', '2024-11-25 10:31:37'),
(6, 'delete_users', 'Erlaubt das Löschen von Benutzern', '2024-11-25 10:31:37', '2024-11-25 10:31:37'),
(7, 'view_customers', 'Kunden sehen', '2024-11-25 16:31:42', '2024-11-25 16:31:42'),
(8, 'view_offers', 'Angebote sehen', '2024-11-25 16:32:01', '2024-11-25 16:32:01'),
(9, 'view_invoices', 'Rechnungen sehen', '2024-11-25 17:57:57', '2024-11-25 17:57:57'),
(10, 'view_sales_analysis', 'Analyse des Umsatzes sehen', '2024-11-25 17:59:03', '2024-11-25 17:59:03'),
(11, 'view_email_list', 'Liste der gesendeten E-Mails sehen', '2024-11-25 17:59:27', '2024-11-25 17:59:27'),
(12, 'manage_users', 'Benutzer verwalten', '2024-11-25 17:59:37', '2024-11-25 17:59:37'),
(13, 'manage_roles', 'Rollen verwalten', '2024-11-25 17:59:47', '2024-11-25 17:59:47'),
(14, 'view_clients', 'Klienten verwalten', '2024-11-25 18:00:03', '2024-11-25 18:00:03'),
(15, 'update_settings', 'Einstellungen sehen', '2024-11-25 18:00:23', '2024-11-25 18:00:23'),
(16, 'logout', 'Ausloggen', '2024-11-25 18:01:59', '2024-11-25 18:01:59'),
(17, 'manage_permissions', 'Rechte bearbeiten', NULL, '2024-11-25 19:13:01');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `personal_access_tokens2`
--

CREATE TABLE `personal_access_tokens2` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `roles`
--

INSERT INTO `roles` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'Administrator mit vollen Rechten', '2024-11-25 10:31:37', '2024-11-25 10:31:37'),
(2, 'editor', 'Benutzer mit Rechten zum Bearbeiten von Inhalten', '2024-11-25 10:31:37', '2024-11-25 10:31:37');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `role_permission`
--

CREATE TABLE `role_permission` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `role_permission`
--

INSERT INTO `role_permission` (`id`, `role_id`, `permission_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, NULL),
(2, 1, 2, NULL, NULL),
(3, 2, 2, NULL, NULL),
(4, 1, 3, NULL, NULL),
(5, 1, 4, NULL, NULL),
(6, 1, 5, NULL, NULL),
(7, 1, 6, NULL, NULL),
(8, 1, 7, NULL, NULL),
(9, 1, 8, NULL, NULL),
(10, 1, 9, NULL, NULL),
(11, 1, 10, NULL, NULL),
(12, 1, 11, NULL, NULL),
(13, 1, 12, NULL, NULL),
(14, 1, 13, NULL, NULL),
(15, 1, 14, NULL, NULL),
(16, 1, 15, NULL, NULL),
(17, 1, 16, NULL, NULL),
(18, 1, 17, NULL, NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `salutations`
--

CREATE TABLE `salutations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `salutations`
--

INSERT INTO `salutations` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Herr', NULL, NULL),
(2, 'Frau', NULL, NULL),
(3, 'Firma', NULL, NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `taxrates`
--

CREATE TABLE `taxrates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `taxrate` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `taxrates`
--

INSERT INTO `taxrates` (`id`, `taxrate`, `created_at`, `updated_at`) VALUES
(1, 0, NULL, NULL),
(2, 20, NULL, NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `units`
--

CREATE TABLE `units` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `unitdesignation` varchar(200) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `units`
--

INSERT INTO `units` (`id`, `unitdesignation`, `created_at`, `updated_at`) VALUES
(1, 'Stk', '2024-11-25 10:31:37', '2024-11-25 10:31:37'),
(2, 'Pau', '2024-11-25 10:31:37', '2024-11-25 10:31:37'),
(3, 'Std', '2024-11-25 10:31:37', '2024-11-25 10:31:37'),
(4, '.', '2024-11-25 10:31:37', '2024-11-25 10:31:37'),
(5, 'h', '2024-11-25 10:31:37', '2024-11-25 10:31:37'),
(6, 'm', '2024-11-25 10:31:37', '2024-11-25 10:31:37');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(100) NOT NULL,
  `normalizedusername` varchar(100) DEFAULT NULL,
  `password` varchar(1000) NOT NULL,
  `name` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `isactive` tinyint(1) NOT NULL DEFAULT 1,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `users`
--

INSERT INTO `users` (`id`, `username`, `normalizedusername`, `password`, `name`, `lastname`, `email`, `role_id`, `isactive`, `client_id`, `email_verified_at`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'lucianbulz', 'LUCIANBULZ', '$2y$12$OdEA0MGImEynuMkFbIZJFua7E6F57yEfuSAOIlLSnn9opmrLDozdu', 'Lucian', 'Bulz', 'lucian@bulz.at', 1, 1, 1, NULL, NULL, '2024-11-25 10:31:37', '2024-11-25 10:31:37');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user_role`
--

CREATE TABLE `user_role` (
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `cashflows`
--
ALTER TABLE `cashflows`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cashflows_client_id_foreign` (`client_id`);

--
-- Indizes für die Tabelle `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `clients_tax_id_foreign` (`tax_id`);

--
-- Indizes für die Tabelle `conditions`
--
ALTER TABLE `conditions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `conditions_client_id_foreign` (`client_id`);

--
-- Indizes für die Tabelle `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customers_tax_id_foreign` (`tax_id`),
  ADD KEY `customers_condition_id_foreign` (`condition_id`),
  ADD KEY `customers_salutation_id_foreign` (`salutation_id`),
  ADD KEY `customers_client_id_foreign` (`client_id`);

--
-- Indizes für die Tabelle `factorrules`
--
ALTER TABLE `factorrules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `factorrules_client_id_foreign` (`client_id`);

--
-- Indizes für die Tabelle `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indizes für die Tabelle `fileuploads`
--
ALTER TABLE `fileuploads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fileuploads_client_id_foreign` (`client_id`);

--
-- Indizes für die Tabelle `invoicepositions`
--
ALTER TABLE `invoicepositions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoicepositions_invoice_id_foreign` (`invoice_id`),
  ADD KEY `invoicepositions_unit_id_foreign` (`unit_id`);

--
-- Indizes für die Tabelle `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoices_customer_id_foreign` (`customer_id`),
  ADD KEY `invoices_tax_id_foreign` (`tax_id`),
  ADD KEY `invoices_condition_id_foreign` (`condition_id`);

--
-- Indizes für die Tabelle `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `messages_customer_id_foreign` (`customer_id`),
  ADD KEY `messages_client_id_foreign` (`client_id`);

--
-- Indizes für die Tabelle `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `offerpositions`
--
ALTER TABLE `offerpositions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `offerpositions_offer_id_foreign` (`offer_id`),
  ADD KEY `offerpositions_unit_id_foreign` (`unit_id`);

--
-- Indizes für die Tabelle `offers`
--
ALTER TABLE `offers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `offers_customer_id_foreign` (`customer_id`),
  ADD KEY `offers_tax_id_foreign` (`tax_id`),
  ADD KEY `offers_condition_id_foreign` (`condition_id`);

--
-- Indizes für die Tabelle `outgoingemails`
--
ALTER TABLE `outgoingemails`
  ADD PRIMARY KEY (`id`),
  ADD KEY `outgoingemails_customer_id_foreign` (`customer_id`),
  ADD KEY `outgoingemails_client_id_foreign` (`client_id`);

--
-- Indizes für die Tabelle `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indizes für die Tabelle `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_unique` (`name`);

--
-- Indizes für die Tabelle `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indizes für die Tabelle `personal_access_tokens2`
--
ALTER TABLE `personal_access_tokens2`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens2_token_unique` (`token`),
  ADD KEY `personal_access_tokens2_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indizes für die Tabelle `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_unique` (`name`);

--
-- Indizes für die Tabelle `role_permission`
--
ALTER TABLE `role_permission`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_permission_role_id_foreign` (`role_id`),
  ADD KEY `role_permission_permission_id_foreign` (`permission_id`);

--
-- Indizes für die Tabelle `salutations`
--
ALTER TABLE `salutations`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `taxrates`
--
ALTER TABLE `taxrates`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_role_id_foreign` (`role_id`),
  ADD KEY `users_client_id_foreign` (`client_id`);

--
-- Indizes für die Tabelle `user_role`
--
ALTER TABLE `user_role`
  ADD PRIMARY KEY (`user_id`,`role_id`),
  ADD KEY `user_role_role_id_foreign` (`role_id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `cashflows`
--
ALTER TABLE `cashflows`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `clients`
--
ALTER TABLE `clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `conditions`
--
ALTER TABLE `conditions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT für Tabelle `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT für Tabelle `factorrules`
--
ALTER TABLE `factorrules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `fileuploads`
--
ALTER TABLE `fileuploads`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `invoicepositions`
--
ALTER TABLE `invoicepositions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=186;

--
-- AUTO_INCREMENT für Tabelle `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=116;

--
-- AUTO_INCREMENT für Tabelle `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT für Tabelle `offerpositions`
--
ALTER TABLE `offerpositions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=125;

--
-- AUTO_INCREMENT für Tabelle `offers`
--
ALTER TABLE `offers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT für Tabelle `outgoingemails`
--
ALTER TABLE `outgoingemails`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT für Tabelle `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT für Tabelle `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `personal_access_tokens2`
--
ALTER TABLE `personal_access_tokens2`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT für Tabelle `role_permission`
--
ALTER TABLE `role_permission`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT für Tabelle `salutations`
--
ALTER TABLE `salutations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT für Tabelle `taxrates`
--
ALTER TABLE `taxrates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT für Tabelle `units`
--
ALTER TABLE `units`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT für Tabelle `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `cashflows`
--
ALTER TABLE `cashflows`
  ADD CONSTRAINT `cashflows_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`);

--
-- Constraints der Tabelle `clients`
--
ALTER TABLE `clients`
  ADD CONSTRAINT `clients_tax_id_foreign` FOREIGN KEY (`tax_id`) REFERENCES `taxrates` (`id`);

--
-- Constraints der Tabelle `conditions`
--
ALTER TABLE `conditions`
  ADD CONSTRAINT `conditions_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`);

--
-- Constraints der Tabelle `customers`
--
ALTER TABLE `customers`
  ADD CONSTRAINT `customers_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`),
  ADD CONSTRAINT `customers_condition_id_foreign` FOREIGN KEY (`condition_id`) REFERENCES `conditions` (`id`),
  ADD CONSTRAINT `customers_salutation_id_foreign` FOREIGN KEY (`salutation_id`) REFERENCES `salutations` (`id`),
  ADD CONSTRAINT `customers_tax_id_foreign` FOREIGN KEY (`tax_id`) REFERENCES `taxrates` (`id`);

--
-- Constraints der Tabelle `factorrules`
--
ALTER TABLE `factorrules`
  ADD CONSTRAINT `factorrules_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `fileuploads`
--
ALTER TABLE `fileuploads`
  ADD CONSTRAINT `fileuploads_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `invoicepositions`
--
ALTER TABLE `invoicepositions`
  ADD CONSTRAINT `invoicepositions_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `invoicepositions_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`);

--
-- Constraints der Tabelle `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_condition_id_foreign` FOREIGN KEY (`condition_id`) REFERENCES `conditions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `invoices_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `invoices_tax_id_foreign` FOREIGN KEY (`tax_id`) REFERENCES `taxrates` (`id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `offerpositions`
--
ALTER TABLE `offerpositions`
  ADD CONSTRAINT `offerpositions_offer_id_foreign` FOREIGN KEY (`offer_id`) REFERENCES `offers` (`id`),
  ADD CONSTRAINT `offerpositions_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`);

--
-- Constraints der Tabelle `offers`
--
ALTER TABLE `offers`
  ADD CONSTRAINT `offers_condition_id_foreign` FOREIGN KEY (`condition_id`) REFERENCES `conditions` (`id`),
  ADD CONSTRAINT `offers_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  ADD CONSTRAINT `offers_tax_id_foreign` FOREIGN KEY (`tax_id`) REFERENCES `taxrates` (`id`);

--
-- Constraints der Tabelle `outgoingemails`
--
ALTER TABLE `outgoingemails`
  ADD CONSTRAINT `outgoingemails_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `outgoingemails_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `role_permission`
--
ALTER TABLE `role_permission`
  ADD CONSTRAINT `role_permission_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_permission_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`),
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);

--
-- Constraints der Tabelle `user_role`
--
ALTER TABLE `user_role`
  ADD CONSTRAINT `user_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_role_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
