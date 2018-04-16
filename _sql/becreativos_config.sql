-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Gegenereerd op: 16 apr 2018 om 14:13
-- Serverversie: 5.6.33
-- PHP-versie: 5.6.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `creativo_webdb`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `becreativos_config`
--

CREATE TABLE `becreativos_config` (
  `id` int(11) NOT NULL,
  `clave` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `valor` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Gegevens worden geëxporteerd voor tabel `becreativos_config`
--

INSERT INTO `becreativos_config` (`id`, `clave`, `valor`) VALUES
(1, 'nombre', 'BE Creativos'),
(2, 'telefono', '+34 965 703 302'),
(3, 'email', 'info@creativos.be'),
(4, 'facebook', 'https://www.facebook.com/disenowebtorrevieja/'),
(5, 'twitter', 'https://twitter.com/'),
(6, 'calle', 'Avda. de los nenúfares s/n'),
(7, 'codigo_postal', '03184'),
(8, 'ciudad', 'Torrevieja'),
(9, 'resultados_por_pagina', '15'),
(10, 'movil', '+34 651 557 615'),
(11, 'provincia', 'Alicante'),
(12, 'pais', 'España'),
(13, 'linkedin', 'https://www.linkedin.com/'),
(14, 'rango_precio', '0,25,50,100,125,150,200,250,300,350,400,450,500,600,700,800,900,1000,2000,3000,4000,5000'),
(63, 'googleplus', 'https://plus.google.com/112409245826134788734'),
(77, 'web', 'www.creativos.be'),
(74, 'lon', '-0.6910636'),
(75, 'lat', '37.9959469'),
(76, 'zoom', '14'),
(78, 'detalle_direccion', 'Vivero de Empresas, despacho 15'),
(79, 'calle_alicante', 'Calle Ramón Gómez Sempere 16, 9ºH'),
(80, 'codigo_postal_alicante', '03008'),
(81, 'telefono_alicante', '+34 651 557 615'),
(82, 'email_alicante', 'alicante@creativos.be'),
(83, 'instagram', 'https://www.instagram.com/becreativos/'),
(84, 'behance', 'https://www.behance.net/BECreativos'),
(85, 'test', 'ing');

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `becreativos_config`
--
ALTER TABLE `becreativos_config`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `becreativos_config`
--
ALTER TABLE `becreativos_config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
