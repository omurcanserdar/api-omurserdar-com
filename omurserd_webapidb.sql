-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Anamakine: localhost:3306
-- Üretim Zamanı: 17 Tem 2020, 22:42:58
-- Sunucu sürümü: 10.3.23-MariaDB
-- PHP Sürümü: 7.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `omurserd_webapidb`
--
CREATE DATABASE IF NOT EXISTS `omurserd_webapidb` DEFAULT CHARACTER SET utf8 COLLATE utf8_turkish_ci;
USE `omurserd_webapidb`;

DELIMITER $$
--
-- Yordamlar
--
DROP PROCEDURE IF EXISTS `sp_kurumsalTabMenuOrtMinMax`$$
CREATE DEFINER=`omurserd`@`localhost` PROCEDURE `sp_kurumsalTabMenuOrtMinMax` (`k_id` INT)  BEGIN
    SELECT tabMenu.ad,COUNT(envanter.id) as ENVANTERSAYISI, ROUND(AVG(fiyat),2) as ORTALAMAFIYAT,MIN(fiyat) AS ENDUSUKFIYAT, MAX(fiyat) as ENYUKSEKFIYAT 
    FROM envanter,tabMenu,kurumsal
    WHERE envanter.tabMenu_id=tabMenu.id
    AND tabMenu.kurumsal_id=kurumsal.id
    AND envanter.kurumsal_id=kurumsal.id
    AND kurumsal.id=k_id
    GROUP BY tabMenu.ad
    ORDER BY ENVANTERSAYISI DESC;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `bireysel`
--

DROP TABLE IF EXISTS `bireysel`;
CREATE TABLE `bireysel` (
  `id` int(11) UNSIGNED NOT NULL,
  `il_id` tinyint(2) UNSIGNED NOT NULL,
  `ilce_id` smallint(5) UNSIGNED NOT NULL,
  `ad` varchar(120) COLLATE utf8_turkish_ci NOT NULL,
  `soyad` varchar(120) COLLATE utf8_turkish_ci NOT NULL,
  `kullaniciadi` varchar(40) COLLATE utf8_turkish_ci DEFAULT NULL,
  `email` varchar(254) COLLATE utf8_turkish_ci NOT NULL,
  `sifre` varchar(254) COLLATE utf8_turkish_ci NOT NULL,
  `kayit_tarihi` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `bireysel`
--

INSERT INTO `bireysel` (`id`, `il_id`, `ilce_id`, `ad`, `soyad`, `kullaniciadi`, `email`, `sifre`, `kayit_tarihi`) VALUES
(1, 34, 422, 'Ömürcan', 'Serdar', 'omurserdarr', 'omurserdarr@gmail.com', 'aa1c8371ebd158fb3966a146e5f9ed45', '2020-04-09 17:56:43'),
(2, 34, 422, 'Ali', 'Veli', 'userbireysel', 'hesap@bireysel.com', '8a5da52ed126447d359e70c05721a8aa', '2020-03-10 10:29:49'),
(3, 34, 454, 'Behzat', 'Çözer', 'behzatc', 'behz@c.net', 'aa1c8371ebd158fb3966a146e5f9ed45', '2020-03-10 10:31:16'),
(4, 77, 928, 'Ercüment', 'Çizer', 'cizercument', 'ercu@ment.org', 'aa1c8371ebd158fb3966a146e5f9ed45', '2020-03-10 10:31:16'),
(5, 61, 802, 'Hüsrev', 'İncir', 'husrevincir', 'husrev@incir.co', 'aa1c8371ebd158fb3966a146e5f9ed45', '2020-03-10 22:13:33'),
(6, 81, 951, 'Selim', 'Derviş', 'derviselim', 'selim@dervis.net', 'aa1c8371ebd158fb3966a146e5f9ed45', '2020-03-10 22:13:33'),
(7, 63, 835, 'Eşref', 'Başgan', 'basganesref', 'esref@basgan.com', 'aaa1c8371ebd158fb3966a146e5f9ed45', '2020-03-10 22:14:28'),
(35, 34, 442, 'Abdurrezzak', 'Kargı', 'arezzak', 'arezzak18@gmail.com', '11529902c1007059cf1009dcea903855', '2020-05-23 15:25:30');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `degerlendirme`
--

DROP TABLE IF EXISTS `degerlendirme`;
CREATE TABLE `degerlendirme` (
  `id` int(11) NOT NULL,
  `siparisKod` varchar(14) CHARACTER SET utf8 COLLATE utf8_turkish_ci NOT NULL,
  `hiz` tinyint(2) UNSIGNED NOT NULL,
  `lezzet` tinyint(2) UNSIGNED NOT NULL,
  `servis` tinyint(2) UNSIGNED NOT NULL,
  `yorum` varchar(120) CHARACTER SET utf8 COLLATE utf8_turkish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `envanter`
--

DROP TABLE IF EXISTS `envanter`;
CREATE TABLE `envanter` (
  `id` int(11) NOT NULL,
  `tabMenu_id` int(11) NOT NULL,
  `ad` varchar(120) COLLATE utf8_turkish_ci NOT NULL,
  `tanim` varchar(150) COLLATE utf8_turkish_ci DEFAULT NULL,
  `fiyat` float NOT NULL,
  `alinabilirMi` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `envanter`
--

INSERT INTO `envanter` (`id`, `tabMenu_id`, `ad`, `tanim`, `fiyat`, `alinabilirMi`) VALUES
(1552, 1, 'Tosto Ayvalık Et Menü', ' Ayvalık Ekmeğine Et Döner Tost + Patates Kızartması + Kutu İçecek', 30, 0),
(1553, 1, 'Tosto Ayvalık Tavuk Menü', ' Ayvalık Ekmeğine Tavuk Döner Tost + Patates Kızartması + Kutu İçecek', 25, 1),
(1554, 1, 'Büyük Burger Menü', ' Büyük Burger + Patates Kızartması + Kutu İçecek', 23, 1),
(1555, 1, 'Doyuran Tavuk Menü', ' Pide Ekmek Arası Tavuk Döner + Patates Kızartması + Kutu İçecek\r', 19, 1),
(1556, 1, 'Goralı Menü', ' Goralı Sandviç + Patates Kızartması + Kutu İçecek', 21, 1),
(1557, 1, 'Islak Hamburger Menü', ' 2 Adet Islak Hamburger + Patates Kızartması + Kutu İçecek', 21, 1),
(1558, 1, 'Kaşarlı Tavuk Döner Dürüm Menü', ' Kaşarlı Tavuk Döner Dürüm + Patates Kızartması + Kutu İçecek', 23, 0),
(1559, 1, 'Doyuran Et Menü', ' Pide Ekmek Arası Et Döner + Patates Kızartması + Kutu İçecek\r', 25, 1),
(1560, 1, 'Kaşarlı Et Dürüm Menü', ' Kaşarlı Et Döner Dürüm + Patates Kızartması + Kutu İçecek\r', 28, 1),
(1561, 2, 'Kahvaltı Tabağı', ' Beyaz peynir, burgu peynir, bal, tereyağı, zeytin, yumurta, kaşar peyniri, Koska Çikos, Karper peyniri, domates, salatalık, kayısı, patates kızartmas', 17.5, 1),
(1562, 2, 'Domatesli Kaşarlı Omlet', NULL, 14, 1),
(1563, 2, 'Sucuklu Yumurta', NULL, 15, 1),
(1564, 2, 'Menemen', NULL, 15, 1),
(1565, 2, 'Kavurmalı Kaşarlı Yumurta', NULL, 19, 1),
(1566, 2, 'Beyaz Peynirli Omlet', NULL, 13, 1),
(1567, 3, 'Tereyağlı Süzme Mercimek Çorbası', NULL, 8, 1),
(1568, 4, 'Beyaz Peynirli Tost', NULL, 8, 1),
(1569, 4, 'Kaşarlı Tost', NULL, 8, 1),
(1570, 4, 'Çift Kaşarlı Tost', NULL, 10, 1),
(1571, 4, 'Salamlı Tost', NULL, 8.5, 1),
(1572, 4, 'Sucuklu Tost', NULL, 11, 1),
(1573, 4, 'Salamlı Kaşarlı Tost', NULL, 10.5, 1),
(1574, 4, 'Sucuklu Kaşarlı Tost', NULL, 13, 1),
(1575, 4, 'Dilli Tost', NULL, 16, 1),
(1576, 4, 'Kavurmalı Tost', NULL, 15, 1),
(1577, 4, 'Dilli Kaşarlı Tost', NULL, 16, 1),
(1578, 4, 'Kavurmalı Kaşarlı Tost', NULL, 17, 1),
(1579, 5, 'Ayvalık Ekmeğine Kaşarlı Tost', NULL, 12.1, 1),
(1580, 5, 'Ayvalık Tostu', ' Amerikan salatası, kaşar peyniri, sucuk, salam, sosis, domates, turşu\r', 16, 1),
(1581, 6, 'Yarım Ekmek Arası Beyaz Peynirli Tost', NULL, 11, 1),
(1582, 6, 'Yarım Ekmek Arası Kaşarlı Tost', NULL, 11, 1),
(1583, 6, 'Yarım Ekmek Arası Karışık Tost', ' Sucuk, kaşar peyniri\r', 19, 1),
(1584, 7, '3/4 Ekmek Arası Et Döner (100 gr.)', NULL, 27, 1),
(1585, 7, 'Tosto Et Döner (60 gr.)', ' Ayvalık ekmeğine et döner, kaşar peyniri, turşu, patates, domates', 15.1, 1),
(1586, 7, 'Bütün Ekmek Arası Et Döner (100 gr.)', NULL, 28, 1),
(1587, 7, 'Et Döner (100 gr.)', NULL, 27, 1),
(1588, 7, 'Et Döner (150 gr.)', NULL, 38, 1),
(1589, 7, 'Et Döner Dürüm (100 gr.)', NULL, 26, 1),
(1590, 7, 'Et Döner Dürüm (60 gr.)', NULL, 17, 1),
(1591, 7, 'İskender (Et Dönerden) (100 gr.)', NULL, 33, 1),
(1592, 7, 'İskender (Et Dönerden) (150 gr.)', NULL, 43, 1),
(1593, 7, 'Kaşarlı Et Döner Dürüm (100 gr.)', NULL, 29, 1),
(1594, 7, 'Kaşarlı Et Döner Dürüm (60 gr.)', NULL, 20, 1),
(1595, 7, 'Pide Arası Et Döner (100 gr.)', NULL, 26, 1),
(1596, 7, 'Pide Arası Et Döner (60 gr.)', NULL, 17, 1),
(1597, 7, 'Pide Arası Kaşarlı Et Döner (60 gr.)', NULL, 20, 1),
(1598, 7, 'Pilav Üstü Et Döner (100 gr.)', NULL, 30, 1),
(1599, 7, 'Pilav Üstü Et Döner (150 gr.)', NULL, 41, 1),
(1600, 7, 'Sandviç Ekmek Arası Et Döner (100 gr.)', NULL, 24.5, 1),
(1601, 7, 'Sandviç Ekmek Arası Et Döner (60 gr.)', NULL, 17, 1),
(1602, 7, 'Yarım Ekmek Arası Et Döner (100 gr.)', NULL, 26, 0),
(1603, 7, 'Yarım Ekmek Arası Et Döner (60 gr.)', NULL, 17, 1),
(1604, 7, 'Yarım Ekmek Arası Kaşarlı Et Döner (60 gr.)', NULL, 20, 1),
(1605, 8, '3/4 Ekmek Arası Tavuk Döner (100 gr.)', NULL, 17, 0),
(1606, 8, 'Tosto Tavuk Döner (60 gr.)', ' Ayvalık ekmeğine tavuk döner, kaşar peyniri, turşu, patates, domates', 14, 1),
(1607, 8, 'Bütün Ekmek Arası Tavuk Döner (100 gr.)', NULL, 17, 1),
(1608, 8, 'İskender (Tavuk Dönerden) (100 gr.)', NULL, 21, 1),
(1609, 8, 'İskender (Tavuk Dönerden) (150 gr.)', NULL, 25, 1),
(1610, 8, 'Kaşarlı Tavuk Döner Dürüm (100 gr.)', NULL, 19, 1),
(1611, 8, 'Kaşarlı Tavuk Döner Dürüm (60 gr.)', NULL, 14, 1),
(1612, 8, 'Pide Arası Kaşarlı Tavuk Döner (60 gr.)', NULL, 14, 1),
(1613, 8, 'Pide Arası Tavuk Döner (100 gr.)', NULL, 16, 1),
(1614, 8, 'Pide Arası Tavuk Döner (60 gr.)', NULL, 10, 0),
(1615, 8, 'Pilav Üstü Tavuk Döner (100 gr.)', NULL, 20, 1),
(1616, 8, 'Pilav Üstü Tavuk Döner (150 gr.)', NULL, 26, 1),
(1617, 8, 'Sandviç Arası Tavuk Döner (100 gr.)', NULL, 15.5, 1),
(1618, 8, 'Sandviç Arası Tavuk Döner (60 gr.)', NULL, 12.5, 1),
(1619, 8, 'Tavuk Döner (100 gr.)', NULL, 17, 1),
(1620, 8, 'Tavuk Döner (150 gr.)', NULL, 23, 0),
(1621, 8, 'Tavuk Döner Dürüm (100 gr.)', NULL, 16, 1),
(1622, 8, 'Tavuk Döner Dürüm (60 gr.)', NULL, 10, 0),
(1623, 8, 'Yarım Ekmek Arası Kaşarlı Tavuk Döner (60 gr.)', NULL, 14, 1),
(1624, 8, 'Yarım Ekmek Arası Tavuk Döner (100 gr.)', NULL, 16, 1),
(1625, 8, 'Yarım Ekmek Arası Tavuk Döner (50 gr.)', NULL, 10, 1),
(1626, 9, 'Hamburger', NULL, 7, 1),
(1627, 9, 'Büyük Burger', ' 120 gr. hamburger köftesi\r', 15, 1),
(1628, 9, 'Islak Hamburger', NULL, 7, 1),
(1629, 9, 'Islak Cheeseburger', NULL, 9, 1),
(1630, 9, 'Cheeseburger', NULL, 9, 1),
(1631, 9, 'Luxburger', NULL, 10.5, 1),
(1632, 10, 'Amerikanlı Sandviç (Sıcak)', NULL, 8, 1),
(1633, 10, 'Dilli Amerikanlı Sandviç (Sıcak)', NULL, 17, 1),
(1634, 10, 'Dilli Füme Sandviç (Sıcak)', NULL, 15, 1),
(1635, 10, 'Goralı Sandviç (Sıcak)', NULL, 10, 1),
(1636, 10, 'Sosisli Sandviç (Sıcak)', NULL, 9, 1),
(1637, 11, 'Kaşarlı Patso', ' Sandviç ekmeğine patates kızartması, kaşar peyniri, turşu, ketçap, mayonez', 14, 1),
(1638, 11, 'Patso', ' Sandviç ekmeğine patates kızartması, turşu, ketçap, mayonez\r', 11, 0),
(1639, 11, 'Sosisli Kaşarlı Patso', NULL, 15, 1),
(1640, 11, 'Sosisli Patso', ' Sandviç ekmeğine patates kızartması, sosis, turşu, ketçap, mayonez\r', 14, 1),
(1641, 12, 'Soğuk Sandviç 4', ' Beyaz peynir, yeşillik, domates, salatalık\r', 10, 0),
(1642, 12, 'Soğuk Sandviç 5', ' Kaşar peyniri, yeşillik, domates, salatalık\r', 10, 1),
(1643, 12, 'Soğuk Sandviç 6', ' Kavurma, kaşar peyniri, domates, turşu\r', 18, 1),
(1644, 12, 'Soğuk Sandviç 7', ' Salam, kaşar peyniri, domates, yeşillik, salatalık', 13, 1),
(1645, 12, 'Soğuk Sandviç 10', ' Beyaz peynir, yumurta, yeşillik, domates, salatalık', 12, 1),
(1646, 12, 'Kendi Sandviçini Yap', ' Seçeceğiniz ekmek, seçeceğiniz malzemeler ile', 3, 1),
(1647, 13, 'Karışık Kumpir', ' Seçeceğiniz malzemeler ile\r', 23, 1),
(1648, 13, 'Sade Kumpir', ' Kaşar peyniri, tereyağı\r', 16, 0),
(1649, 14, 'Patates Kızartması', NULL, 11, 1),
(1650, 14, 'Ketçap', NULL, 0.5, 1),
(1651, 14, 'Mayonez', NULL, 0.5, 1),
(1652, 15, 'Kazandibi', NULL, 9.5, 1),
(1653, 15, 'Supangle', NULL, 9.5, 1),
(1654, 15, 'Sütlaç', NULL, 9.5, 1),
(1655, 16, 'Coca-Cola (33 cl.)', NULL, 6, 1),
(1656, 16, 'Coca-Cola Light (33 cl.)', NULL, 6, 1),
(1657, 16, 'Coca-Cola Şekersiz (33 cl.)', NULL, 6, 1),
(1658, 16, 'Fanta (33 cl.)', NULL, 6, 1),
(1659, 16, 'Sprite (33 cl.)', NULL, 6, 1),
(1660, 16, 'Fuse Tea (33 cl.)', NULL, 6, 1),
(1661, 16, 'Cappy (33 cl.)', NULL, 6, 0),
(1662, 16, 'Ayran (30 cl.)', NULL, 6, 1),
(1663, 16, 'Açık Ayran', ' (Bardak)', 6, 1),
(1664, 16, 'Meyveli Soda (20 cl.)', NULL, 4.5, 0),
(1665, 16, 'Soda (20 cl.)', NULL, 3, 1),
(1666, 16, 'Su (20 cl.)', NULL, 2, 1),
(1667, 16, 'Coca-Cola (1 L.)', NULL, 9, 0),
(1668, 16, 'Coca-Cola Light (1 L.)', NULL, 9, 0),
(1669, 16, 'Coca-Cola Şekersiz (1 L.)', NULL, 9, 1),
(1670, 16, 'Fanta (1 L.)', NULL, 9, 1),
(1671, 16, 'Su (1,5 L.)', NULL, 4, 1),
(1672, 16, 'Coca-Cola (2,5 L.)', NULL, 12, 1),
(1673, 16, 'Fanta (2,5 L.)', NULL, 12, 1),
(1674, 17, 'Ballı Muzlu Süt', ' (Bardak)\r', 11, 1),
(1675, 17, 'Elma Suyu', ' (Bardak)\r', 9, 0),
(1676, 17, 'Greyfurt Suyu', ' (Bardak)\r', 9, 1),
(1677, 17, 'Havuç Elma Suyu', ' (Bardak)\r', 9, 0),
(1678, 17, 'Havuç Suyu', ' (Bardak)', 9, 1),
(1679, 17, 'Kivi Elma Suyu', ' (Bardak)', 11, 1),
(1680, 17, 'Kivi Nar Suyu', ' (Bardak)', 15, 1),
(1681, 17, 'Kivi Portakal Suyu', ' (Bardak)', 11, 1),
(1682, 17, 'Nar Suyu', ' (Bardak)', 13, 1),
(1683, 17, 'Portakal Elma Suyu', ' (Bardak)', 9, 1),
(1685, 17, 'Portakal Havuç Suyu', ' (Bardak)', 9, 1),
(1688, 17, 'Portakal Suyu', ' (Bardak)', 9, 1),
(1689, 17, 'Çikolatalı Atom Extra', ' (Bardak) Havuç, elma, portakal, muz, kivi, bal, çikolata\r', 12, 1),
(1690, 17, 'Kokteyl Atom', ' (Bardak) Havuç, elma, portakal, muz, kivi, bal\r', 12.5, 0),
(1728, 22, 'Çiğ Köfte Dürüm Menü', 'çok acı bu çiğköfte', 22, 1),
(1729, 22, 'Çiğ Köfte Dürüm', ' 90 gr. çiğ köfte, isteğe göre domates, limon, maydanoz, roka, salatalık turşusu, süs biberi\r', 5.5, 1),
(1730, 22, 'Mega Çiğ Köfte Dürüm', ' 125 gr. çiğ köfte, isteğe göre domates, limon, maydanoz, roka, salatalık turşusu, süs biberi\r', 8.5, 1),
(1731, 22, 'Ultra Mega Çiğ Köfte Dürüm', ' 150 gr. çiğ köfte, isteğe göre domates, limon, maydanoz, roka, salatalık turşusu, süs biberi\r', 9, 1),
(1732, 22, 'Duble Mega Çiğ Köfte Dürüm', ' 175 gr. çiğ köfte, isteğe göre domates, limon, maydanoz, roka, salatalık turşusu, süs biberi', 10, 1),
(1733, 22, 'Çiğ Köfte (200 gr.)', ' (1 Kişilik) 1/4 göbek marul, 1/4 limon, 2 adet lavaş, nar ekşisi, acı sos, roka, maydanoz ile', 10, 1),
(1734, 22, 'Çiğ Köfte (300 gr.)', ' (3 Kişilik) yarım kilo göbek marul', 27, 1),
(1735, 22, 'Çiğ Köfte (600 gr.)', ' (3 Kişilik) 1/2 göbek marul, 1/2 limon, 6 adet lavaş, nar ekşisi, acı sos, roka, maydanoz ile', 25, 1),
(1736, 22, 'Çiğ Köfte (1 kg.)', ' (5 Kişilik) Tam göbek marul, tam limon, 10 adet lavaş, nar ekşisi, acı sos, roka, maydanoz ile', 40, 0),
(1737, 23, 'Amerikan Salatası (250 gr.)', NULL, 6, 1),
(1738, 23, 'Rus Salatası (250 gr.)', NULL, 6, 1),
(1739, 23, 'Acılı Ezme (250 gr.)', NULL, 6, 1),
(1740, 24, 'Fırın Sütlaç', NULL, 6, 1),
(1741, 24, 'Karamelli Trileçe', NULL, 7, 1),
(1742, 24, 'Kazandibi', NULL, 6, 1),
(1743, 24, 'Profiterol', NULL, 6, 1),
(1744, 25, 'Yeşillik Tabağı', NULL, 5, 1),
(1745, 25, 'Acı Sos', NULL, 1, 1),
(1746, 25, 'Nar Ekşisi', NULL, 1, 1),
(1747, 25, 'Lavaş (Adet)', NULL, 0.5, 1),
(1748, 26, 'Coca-Cola (33 cl.)', NULL, 4, 0),
(1749, 26, 'Fanta (33 cl.)', NULL, 4, 1),
(1750, 26, 'Cappy (33 cl.)', NULL, 4, 1),
(1751, 26, 'Şalgam Suyu (33 cl.)', NULL, 3, 1),
(1752, 26, 'Ayran (29 cl.)', NULL, 3, 1),
(1753, 26, 'Ayran (20 cl.)', NULL, 2, 1),
(1754, 26, 'Meyveli Soda (20 cl.)', NULL, 2, 1),
(1755, 26, 'Soda (20 cl.)', NULL, 1.5, 1),
(1756, 26, 'Su (50 cl.)', NULL, 1.5, 1),
(1757, 26, 'Coca-Cola (1 L.)', NULL, 7, 1),
(1758, 26, 'Coca-Cola Light (1 L.)', NULL, 7, 1),
(1759, 26, 'Coca-Cola Şekersiz (1 L.)', NULL, 7, 0),
(1760, 26, 'Fanta (1 L.)', NULL, 7, 1),
(1761, 26, 'Ayran (1 L.)', NULL, 7, 1),
(1763, 29, 'Karışık Tost', ' Kaşar peyniri, sucuk\r', 8, 1),
(1764, 30, 'Tavuk Kanat', ' Közlenmiş domates, közlenmiş biber, sumaklı soğan, bulgur pilavı veya pirinç pilavı\r', 17, 1),
(1765, 30, 'Tavuk Kelebek', ' Közlenmiş domates, közlenmiş biber, sumaklı soğan, bulgur pilavı veya pirinç pilavı\r', 17, 1),
(1766, 30, 'Izgara Köfte', ' Közlenmiş domates, közlenmiş biber, sumaklı soğan, bulgur pilavı veya pirinç pilavı\r', 17, 0),
(1767, 30, 'Karışık Izgara', ' Izgara köfte, kasap sucuk, kemiksiz tavuk incik. Közlenmiş domates, közlenmiş biber, sumaklı soğan, bulgur pilavı veya pirinç pilavı\r', 20, 0),
(1768, 31, 'Ekmek Arası Köfte', ' Domates, marul, soğan\r', 17, 0),
(1769, 31, 'Ekmek Arası Sucuk', ' Domates, marul, soğan\r', 17, 1),
(1770, 32, 'Mantı (200 gr.)', NULL, 18, 0),
(1771, 33, 'Yedigün (33 cl.)', NULL, 5, 0),
(1772, 33, 'Coca-Cola (1 L.)', NULL, 7, 1),
(1773, 33, 'Sprite (33 cl.)', NULL, 5, 1),
(1774, 33, 'Sprite (1 L.)', NULL, 7, 1),
(1775, 33, 'Şalgam Suyu (30 cl.)', NULL, 4, 1),
(1776, 33, 'Fruko (1 L.)', NULL, 7, 0),
(1777, 33, 'Şalgam Suyu (33 cl.)', NULL, 4, 1),
(1778, 33, 'Meyveli Soda (20 cl.)', NULL, 3, 1),
(1779, 33, 'Fuse Tea (33 cl.)', NULL, 5, 1),
(1780, 33, 'Coca-Cola Şekersiz (33 cl.)', NULL, 5, 0),
(1781, 33, 'Fanta (33 cl.)', NULL, 5, 0),
(1782, 33, 'Coca-Cola (33 cl.)', NULL, 5, 1),
(1783, 33, 'Pepsi (1 L.)', NULL, 7, 0),
(1784, 33, 'Pepsi Max (1 L.)', NULL, 7, 1),
(1785, 33, 'Su (50 cl.)', NULL, 2, 1),
(1786, 33, '7UP (1 L.)', NULL, 7, 1),
(1787, 33, 'Pepsi (33 cl.)', NULL, 5, 0),
(1788, 33, 'Soda (20 cl.)', NULL, 2, 1),
(1789, 33, 'Fruko (33 cl.)', NULL, 5, 1),
(1790, 33, 'Tropicana (33 cl.)', NULL, 5, 0),
(1791, 33, 'Coca-Cola Light (1 L.)', NULL, 7, 1),
(1792, 33, 'Coca-Cola Light (33 cl.)', NULL, 5, 1),
(1793, 33, 'Yedigün (1 L.)', NULL, 7, 1),
(1794, 33, 'Cappy (33 cl.)', NULL, 5, 0),
(1795, 33, 'Tamek (33 cl.)', NULL, 5, 1),
(1796, 33, 'Coca-Cola Şekersiz (1 L.)', NULL, 7, 1),
(1797, 33, 'Pepsi Max (33 cl.)', NULL, 5, 1),
(1798, 33, '7UP (33 cl.)', NULL, 5, 0),
(1799, 33, 'Pepsi Light (33 cl.)', NULL, 5, 0),
(1800, 33, 'Pepsi Light (1 L.)', NULL, 7, 1),
(1801, 33, 'Ayran (20 cl.)', NULL, 2, 1),
(1802, 33, 'Fanta (1 L.)', NULL, 7, 1),
(1803, 33, 'Ayran (30 cl.)', NULL, 4, 0),
(1804, 34, 'Poşet', NULL, 0.25, 0),
(1805, 35, 'Ekmek Arası Tavuk Döner Menü', ' Ekmek Arası Tavuk Döner (100 gr. tavuk döner) + Patates Kızartması + Ayran (30 cl.)\r', 15, 1),
(1806, 36, 'Ezogelin Çorbası', NULL, 6, 0),
(1807, 37, 'Tavuk Döner', ' Domates, marul, turşu, patates kızartması ile\r', 9, 0),
(1808, 37, 'Pilav & Tavuk Döner', ' 100 gr. pilav üstü tavuk döner, patates kızartması, domates, marul, turşu, ketçap, mayonez\r', 16, 1),
(1809, 37, 'Pilav Üstü Tavuk Döner', NULL, 16, 0),
(1810, 37, 'Tophane Ekmeğine Tavuk Döner', ' Domates, göbek marul, turşu, patates kızartması\r', 11, 1),
(1811, 37, 'Ekmek Arası Tavuk Döner', ' Domates, göbek marul, turşu, patates kızartması\r', 11, 1),
(1812, 37, 'Kaşarlı Tavuk Döner Dürüm', ' Domates, göbek marul, turşu, patates kızartması\r', 11, 1),
(1813, 38, 'Yarım Ekmek Arası Izgara Köfte', ' Soğan, domates, göbek marul, patates kızartması\r', 15, 0),
(1814, 39, 'Kuru Fasulye', NULL, 12, 1),
(1815, 39, 'Pirinç Pilavı', NULL, 7, 1),
(1816, 39, 'Pilav Üstü Kuru Fasulye', NULL, 9, 0),
(1817, 40, 'Patates Kızartması', NULL, 5, 1),
(1818, 41, 'Sütlaç', NULL, 6, 0),
(1819, 42, 'Coca-Cola (33 cl.)', NULL, 4, 0),
(1820, 42, 'Coca-Cola Light (33 cl.)', NULL, 4, 0),
(1821, 42, 'Coca-Cola Şekersiz (33 cl.)', NULL, 4, 1),
(1822, 42, 'Fanta (33 cl.)', NULL, 4, 0),
(1823, 42, 'Sprite (33 cl.)', NULL, 4, 0),
(1824, 42, 'Cappy (33 cl.)', NULL, 4, 0),
(1825, 42, 'Fuse Tea (33 cl.)', NULL, 4, 0),
(1826, 42, 'Ayran (30 cl.)', NULL, 2.5, 0),
(1827, 42, 'Meyveli Soda (20 cl.)', NULL, 2.5, 0),
(1828, 42, 'Soda (20 cl.)', NULL, 2.5, 1),
(1829, 42, 'Su (50 cl.)', NULL, 1.5, 0),
(1830, 42, 'Coca-Cola (1 L.)', NULL, 7, 1),
(1831, 42, 'Coca-Cola Light (1 L.)', NULL, 7, 1),
(1832, 42, 'Coca-Cola Şekersiz (1 L.)', NULL, 7, 0),
(1833, 42, 'Fanta (1 L.)', NULL, 7, 0),
(1834, 42, 'Sprite (1 L.)', NULL, 7, 1),
(1835, 43, 'Poşet', NULL, 0.25, 1),
(1836, 44, 'Menü 1', ' Kıymalı Pide + İçecek (1 L.)\r', 20, 1),
(1837, 44, 'Menü 2', ' 10 Adet Lahmacun + Coca-Cola (2,5 L.) (Lahmacunların acı seçimini sipariş notunda belirtiniz.)\r', 70, 1),
(1838, 44, 'Menü 3', ' Kavurmalı Pide + İçecek (1 L.)\r', 22, 1),
(1839, 44, 'Menü 4', ' Peynirli Pide + Lahmacun (2 Adet) + İçecek (1 L.)\r', 28, 0),
(1840, 44, 'Menü 5', ' Kavurmalı Pide + Ayran (33 cl.)\r', 20, 0),
(1841, 44, 'Menü 6', ' Kıymalı Pide + Peynirli Pide + İçecek (1 L.)\r', 30, 1),
(1842, 44, 'Menü 7', ' Sucuklu Kaşarlı Pide + Ayran (33 cl.)\r', 17, 0),
(1843, 45, 'Kuymak', ' Ekmek ile\r', 15, 0),
(1844, 45, 'Menemen', ' Ekmek ile\r', 15, 1),
(1845, 46, 'Lahmacun', ' Limon, maydanoz ile\r', 7, 0),
(1846, 46, 'Peynirli Pide', ' Limon, maydanoz ile\r', 13, 1),
(1847, 46, 'Sucuklu Kaşarlı Pide', ' Limon, maydanoz ile\r', 17, 1),
(1848, 46, 'Kıymalı Pide', ' Limon, maydanoz ile\r', 17, 1),
(1849, 46, 'Kuşbaşılı Pide', ' Limon, maydanoz ile\r', 18, 0),
(1850, 46, 'Karışık Pide', ' Kıyma, kuşbaşı, kaşar peyniri. Limon, maydanoz ile\r', 19, 0),
(1851, 46, 'Kavurmalı Pide', ' Limon, maydanoz ile\r', 20, 1),
(1852, 47, 'Ekmek Arası Et Sac Kavurma', ' İsteğe göre soğan\r', 15, 1),
(1853, 47, 'Ekmek Arası Tavuk Sac Kavurma', ' İsteğe göre soğan\r', 10, 0),
(1854, 48, 'Coca-Cola (33 cl.)', NULL, 4, 0),
(1855, 48, 'Coca-Cola Light (33 cl.)', NULL, 4, 0),
(1856, 48, 'Coca-Cola Şekersiz (33 cl.)', NULL, 4, 0),
(1857, 48, 'Fanta (33 cl.)', NULL, 4, 0),
(1858, 48, 'Sprite (33 cl.)', NULL, 3, 1),
(1859, 48, 'Cappy (33 cl.)', NULL, 4, 1),
(1860, 48, 'Fuse Tea (33 cl.)', NULL, 4, 1),
(1861, 48, 'Ayran (30 cl.)', NULL, 2.5, 0),
(1862, 48, 'Ayran (20 cl.)', NULL, 1.5, 1),
(1863, 48, 'Meyveli Soda (20 cl.)', NULL, 2.5, 0),
(1864, 48, 'Soda (20 cl.)', NULL, 2, 0),
(1865, 48, 'Su (50 cl.)', NULL, 1, 0),
(1866, 48, 'Coca-Cola (1 L.)', NULL, 5, 0),
(1867, 48, 'Coca-Cola Light (1 L.)', NULL, 5, 0),
(1868, 48, 'Coca-Cola Şekersiz (1 L.)', NULL, 5, 1),
(1869, 48, 'Fanta (1 L.)', NULL, 5, 1),
(1870, 49, 'Poşet', NULL, 0.25, 0),
(1871, 50, 'Adana Kebap (1 Şiş)', ' Közlenmiş biber, soğan, limon, nane, maydanoz, soğan salatası, domates ile\r', 19, 0),
(1872, 50, 'Adana Kebap (2 Şiş)', ' Közlenmiş biber, soğan, limon, nane, maydanoz, soğan salatası, domates ile\r', 30, 0),
(1873, 50, 'Urfa Kebap (1 Şiş)', ' Közlenmiş biber, soğan, limon, nane, maydanoz, soğan salatası, domates ile\r', 19, 1),
(1874, 50, 'Urfa Kebap (2 Şiş)', ' Közlenmiş biber, soğan, limon, nane, maydanoz, soğan salatası, domates ile\r', 30, 0),
(1875, 51, 'Yarım Ekmek Arası Ciğer', ' Közlenmiş biber, soğan, limon, nane, maydanoz, soğan salatası, domates ile\r', 13, 1),
(1876, 51, 'Yarım Ekmek Arası Dalak', ' Közlenmiş biber, soğan, limon, nane, maydanoz, soğan salatası, domates ile\r', 13, 0),
(1877, 51, 'Yarım Ekmek Arası Kuşbaşı', ' Közlenmiş biber, soğan, limon, nane, maydanoz, soğan salatası, domates ile\r', 19, 0),
(1878, 51, 'Yarım Ekmek Arası Tavuk', ' Közlenmiş biber, soğan, limon, nane, maydanoz, soğan salatası, domates ile\r', 12, 1),
(1879, 51, 'Yarım Ekmek Arası Terbiyesiz Kuşbaşı', ' Közlenmiş biber, soğan, limon, nane, maydanoz, soğan salatası, domates ile\r', 19, 0),
(1880, 51, 'Yarım Ekmek Arası Terbiyesiz Tavuk', ' Közlenmiş biber, soğan, limon, nane, maydanoz, soğan salatası, domates ile\r', 12, 0),
(1881, 51, 'Yarım Ekmek Arası Yürek', ' Közlenmiş biber, soğan, limon, nane, maydanoz, soğan salatası, domates ile\r', 13, 0),
(1882, 51, 'Tam Ekmek Arası Ciğer', ' Közlenmiş biber, soğan, limon, nane, maydanoz, soğan salatası, domates ile\r', 20, 1),
(1883, 51, 'Tam Ekmek Arası Dalak', ' Közlenmiş biber, soğan, limon, nane, maydanoz, soğan salatası, domates ile\r', 20, 1),
(1884, 51, 'Tam Ekmek Arası Kuşbaşı', ' Közlenmiş biber, soğan, limon, nane, maydanoz, soğan salatası, domates ile\r', 30, 0),
(1885, 51, 'Tam Ekmek Arası Tavuk', ' Közlenmiş biber, soğan, limon, nane, maydanoz, soğan salatası, domates ile\r', 19, 0),
(1886, 51, 'Tam Ekmek Arası Terbiyesiz Kuşbaşı', ' Közlenmiş biber, soğan, limon, nane, maydanoz, soğan salatası, domates ile\r', 30, 1),
(1887, 51, 'Tam Ekmek Arası Terbiyesiz Tavuk', ' Közlenmiş biber, soğan, limon, nane, maydanoz, soğan salatası, domates ile\r', 19, 0),
(1888, 51, 'Tam Ekmek Arası Yürek', ' Közlenmiş biber, soğan, limon, nane, maydanoz, soğan salatası, domates ile\r', 20, 1),
(1889, 52, 'Kızartma İçli Köfte (Adet)', NULL, 6, 0),
(1890, 53, 'Fıstıklı Kaymaklı Katmer', NULL, 16, 0),
(1891, 54, 'Coca-Cola (33 cl.)', NULL, 4, 1),
(1892, 54, 'Fanta (33 cl.)', NULL, 4, 1),
(1893, 54, 'Cappy (33 cl.)', NULL, 4, 0),
(1894, 54, 'Pepsi (33 cl.)', NULL, 4, 0),
(1895, 54, 'Pepsi Max (33 cl.)', NULL, 4, 0),
(1896, 54, 'Yedigün (33 cl.)', NULL, 4, 1),
(1897, 54, 'Fruko (33 cl.)', NULL, 4, 1),
(1898, 54, 'Tropicana (33 cl.)', NULL, 4, 1),
(1899, 54, 'Lipton Ice Tea (33 cl.)', NULL, 4, 1),
(1900, 54, 'Şalgam Suyu (33 cl.)', ' Acılı\r', 4, 0),
(1901, 54, 'Ayran (30 cl.)', NULL, 3, 0),
(1902, 54, 'Açık Ayran (20 cl.)', NULL, 2.5, 1),
(1903, 54, 'Meyveli Soda (20 cl.)', NULL, 4, 0),
(1904, 54, 'Su (50 cl.)', NULL, 1.5, 0),
(1905, 55, 'Lahmacun', NULL, 7.5, 0),
(1906, 55, 'Cevizli Lahmacun', NULL, 9, 0),
(1907, 55, 'Yumurtalı Lahmacun', NULL, 9, 0),
(1908, 56, 'Kıymalı Karadeniz Pidesi', ' Nane, maydanoz, soğan salatası, karalahana, limon ile\r', 18, 0),
(1909, 56, 'Kaşarlı Pide', ' Nane, maydanoz, soğan salatası, karalahana, limon ile\r', 17, 0),
(1910, 56, 'Kıymalı Pide', ' Nane, maydanoz, soğan salatası, karalahana, limon ile\r', 20, 1),
(1911, 56, 'Karışık Pide', ' Kıyma, kaşar peyniri, kuşbaşı. Nane, maydanoz, soğan salatası, karalahana, limon ile\r', 20, 1),
(1912, 56, 'Sucuklu Kaşarlı Pide', ' Nane, maydanoz, soğan salatası, karalahana, limon ile\r', 18, 0),
(1913, 56, 'Kuşbaşılı Pide', ' Nane, maydanoz, soğan salatası, karalahana, limon ile\r', 20, 0),
(1914, 57, 'Pepsi (33 cl.)', NULL, 4, 1),
(1915, 57, 'Yedigün (33 cl.)', NULL, 4, 1),
(1916, 57, 'Yedigün Portakal (33 cl.)', NULL, 4, 1),
(1917, 57, 'Tropicana (33 cl.)', NULL, 4, 1),
(1918, 57, 'Ayran (20 cl.)', NULL, 1.5, 1),
(1919, 57, 'Su (50 cl.)', NULL, 1.5, 0),
(1920, 58, 'Poşet', NULL, 0.25, 0),
(1921, 59, 'Standart Lahmacun', ' Dana kıyma, soğan, domates, biber, maydanoz. Salata ile\r', 7, 1),
(1922, 59, 'Acılı Lahmacun', ' Dana kıyma, soğan, domates, biber, maydanoz, özel acı sos. Salata ile\r', 7, 1),
(1923, 59, 'My Lahmacun', ' Dana kıyma, soğan, domates, biber, maydanoz, kaşar peyniri. Salata ile\r', 9.5, 1),
(1924, 60, 'Kıymalı Pide', ' Dana kıyma, soğan, domates, biber. Salata ile\r', 13, 0),
(1925, 60, 'My Pidem Special Pide', ' Kıyma, kuşbaşı, kaşar peyniri, kavurma, sucuk\r', 26, 1),
(1926, 60, 'Bohça Special Pide', ' Kavrulmuş kıyma, kavurma, sucuk, pastırma, kaşar peyniri\r', 43, 1),
(1927, 60, 'Kavurmalı Pastırmalı Kaşarlı Pide', NULL, 39, 0),
(1928, 60, 'Toblerone Pide', ' Toblerone çikolata, muz, fındık, pudra şekeri\r', 25, 1),
(1929, 60, 'Kuşbaşılı Pide', ' Dana kuşbaşı, domates, biber. Salata ile\r', 16, 0),
(1930, 60, 'Kaşarlı Pide', ' Kaşar peyniri. Salata ile\r', 16, 0),
(1931, 60, 'Karışık Pide', ' Dana kıyma, dana kuşbaşı, kasap sucuk, kaşar peyniri, domates, biber. Salata ile\r', 18, 0),
(1932, 60, 'Trabzon Pidesi (Yuvarlak)', ' Dana kıyma, dana kuşbaşı, kasap sucuk, domates, biber, tereyağı. Salata ile\r', 20, 1),
(1933, 60, 'Trabzon Yağlısı', ' Kaşar peyniri, köy yumurtası, tereyağı. Salata ile\r', 22, 0),
(1934, 60, 'Kapalı Kavurmalı Pide', ' Dana kavurma. Salata ile\r', 20, 1),
(1935, 60, 'Kavurmalı Kaşarlı Pide', ' Dana kavurma, kaşar peyniri. Salata ile\r', 23, 1),
(1936, 61, 'Coca-Cola (33 cl.)', NULL, 4, 1),
(1937, 61, 'Coca-Cola Şekersiz (33 cl.)', NULL, 4, 0),
(1938, 61, 'Fanta (33 cl.)', NULL, 4, 1),
(1939, 61, 'Sprite (33 cl.)', NULL, 4, 0),
(1940, 61, 'Cappy (33 cl.)', NULL, 4, 0),
(1941, 61, 'Fuse Tea (33 cl.)', NULL, 4, 0),
(1942, 61, 'Şalgam Suyu (30 cl.)', NULL, 4, 0),
(1943, 61, 'Ayran (20 cl.)', NULL, 2, 1),
(1944, 61, 'Ayran (30 cl.)', NULL, 3, 1),
(1945, 61, 'Meyveli Soda (20 cl.)', NULL, 2, 1),
(1946, 61, 'Soda (20 cl.)', NULL, 1.5, 0),
(1947, 61, 'Su (50 cl.)', NULL, 1.5, 0),
(1948, 61, 'Coca-Cola (1 L.)', NULL, 6, 1),
(1949, 61, 'Coca-Cola Şekersiz (1 L.)', NULL, 6, 0),
(1950, 61, 'Fanta (1 L.)', NULL, 6, 0),
(1951, 61, 'Sprite (1 L.)', NULL, 6, 0),
(1952, 61, 'Ayran (1 L.)', NULL, 6.5, 0),
(1953, 62, 'Poşet', NULL, 0.25, 1),
(1954, 63, 'Sporcu Çocuk Menüsü 1', ' Izgara Köfte (120 gr.) + Eğlenceli Makarnalar + Yoğurt + Muz\r', 26, 1),
(1955, 63, 'Sporcu Çocuk Menüsü 2', ' Tavuk (120 gr.) + Eğlenceli Makarnalar + Yoğurt + Muz\r', 22, 0),
(1956, 63, 'Günlük Menü 1', ' Günün Çorbası + Tavuk + Kepekli Bulgur Pilavı + Salata + Yoğurt + Meyve\r', 25, 1),
(1957, 63, 'Günlük Menü 2', ' Günün Çorbası + Köfte + Kepekli Bulgur Pilavı + Salata + Yoğurt + Meyve\r', 29, 1),
(1958, 64, 'Mevsim Sebzeleri Çorbası', NULL, 5, 1),
(1959, 65, 'Mücver', ' Seçeceğiniz 2 adet yan ürün ile\r', 25, 1),
(1960, 65, 'Levrek', NULL, 28, 0),
(1961, 65, 'Izgara Biftek', ' 150 gr. biftek, 150 - 200 gr. karbonhidrat ve mineral (Seçeceğiniz 2 adet yan ürün ile)\r', 35, 0),
(1962, 65, 'Izgara Antrikot', ' 150 gr. antrikot, 150 - 200 gr. karbonhidrat ve mineral (Seçeceğiniz 2 adet yan ürün ile)\r', 35, 1),
(1963, 65, 'Haşlanmış Tavuk', ' 150 gr. tavuk, 150 - 200 gr. karbonhidrat ve mineral (Seçeceğiniz 2 adet yan ürün ile)\r', 22, 1),
(1964, 65, 'Izgara Hindi', ' 150 gr. hindi, 150 - 200 gr. karbonhidrat ve mineral (Seçeceğiniz 2 adet yan ürün ile)\r', 24, 0),
(1965, 65, 'Izgara Ciğer', ' 150 gr. ciğer, 150 - 200 gr. karbonhidrat ve mineral (Seçeceğiniz 2 adet yan ürün ile)\r', 25, 0),
(1966, 65, 'Izgara Köfte', ' 150 gr. ızgara köfte, 150 - 200 gr. karbonhidrat ve mineral (Seçeceğiniz 2 adet yan ürün ile)\r', 26, 1),
(1967, 65, 'Marine Edilmiş İncik Tavuk (150 gr.)', ' 150 gr. karbonhidrat ve mineral\r', 24, 1),
(1968, 65, 'Marine Edilmiş Fileto Tavuk (150 gr.)', ' 150 gr. karbonhidrat ve mineral\r', 24, 1),
(1969, 65, 'Cheddar Köfte (150 gr.)', ' 150 gr. karbonhidrat ve mineral\r', 27, 0),
(1970, 65, 'Sfood Hindi Köfte (150 gr.)', ' 150 gr. karbonhidrat ve mineral\r', 25, 0),
(1971, 65, 'Tavuk İncik', ' 150 gr. ızgara incik,150-200 gr. karbonhidrat ve mineral. Seçeceğiniz 2 adet yan ürün ile\r', 22, 1),
(1972, 65, 'Ton Balığı', ' 103 gr. ton balığı, 150 - 200 gr. karbonhidrat ve mineral (Seçeceğiniz 2 adet yan ürün ile)\r', 23, 1),
(1973, 65, 'Izgara Tavuk', ' 150 gr. ızgara tavuk, 150 - 200 gr. karbonhidrat ve mineral (Seçeceğiniz 2 adet yan ürün ile)\r', 22, 1),
(1974, 66, 'Kinoalı Ekmeğe Peynirli Tost', ' Mevsim salata, mevsim meyveleri ile\r', 17, 1),
(1975, 66, 'Bahar Hafifliği 3', ' Tam buğdaylı lavaş, tavuk, ezme, domates, yoğurt\r', 18, 0),
(1976, 66, 'Bahar Hafifliği 4', ' Tam buğdaylı lavaş, tavuk, ezme, domates, mevsim meyvesi\r', 18, 1),
(1977, 66, 'Bahar Hafifliği 5', ' Tam buğdaylı lavaş, tavuk, ezme, domates, salata\r', 18, 1),
(1978, 66, 'Hindi Füme', ' Kinoalı ve chia tohumlu tam buğday ekmek, sürme peynir, cheddar peyniri, salata, mevsim meyvesi, mevsimsel mineral\r', 19, 0),
(1979, 66, 'Tam Buğday Lavaş Köfte 1', ' Köfte (100 gr.). Salata, semizotu ile\r', 21, 0),
(1980, 66, 'Tam Buğday Lavaş Köfte 2', ' Köfte (100 gr.). Salata, yoğurtlu ıspanak ile\r', 21, 0),
(1981, 66, 'Tam Buğday Lavaş Köfte 3', ' Köfte (100 gr.). Yoğurt, brokoli ile\r', 21, 1),
(1982, 66, 'Tam Buğday Lavaş Köfte 4', ' Köfte (100 gr.). Brokoli, meyve ile\r', 21, 1),
(1983, 66, 'Tam Buğday Cheddarlı Tost', ' Salata, 2 adet mineral ile\r', 17, 1),
(1984, 67, 'Tavuklu Salata', ' 100 gr. tavuk, roka, dereotu, Akdeniz yeşillikleri, kıvırcık marul, göbek marul, galeta, zeytinyağı, caju\r', 18, 0),
(1985, 67, 'Ton Balıklı Salata', ' 100 gr. ton balığı, roka, dereotu, Akdeniz yeşillikleri, kıvırcık marul, göbek marul, galeta, zeytinyağı, caju\r', 19, 0),
(1986, 67, 'Maş Fasülyesi Salatası', ' 100 gr. tavuk, maydanoz, maş fasulyesi, roka, dereotu, Akdeniz marul, göbek marul, tere, limon, zeytinyağı\r', 19, 0),
(1987, 67, 'Chia Salatası', ' 100 gr. tavuk, chia, maydanoz, roka, tere, dereotu, Akdeniz marul, göbek marul, limon, zeytinyağı\r', 19, 1),
(1988, 67, 'Mevsim Meyveleri Salatası', NULL, 18, 0),
(1989, 67, 'Börülceli Ton Balığı Salatası', ' 100 gr. ton balığı, haşlanmış börülce, roka, tere, maydanoz, dereotu, Akdeniz yeşilliği, göbek marul\r', 19, 1),
(1990, 67, 'Börülceli Tavuk Salatası', ' 100 gr. tavuk, haşlanmış börülce, roka, tere, dereotu, maydanoz, Akdeniz yeşilliği, göbek marul\r', 19, 0),
(1991, 68, 'Ayran (20 cl.)', NULL, 3, 1),
(1992, 68, 'Kinoalı Ayran', NULL, 3.5, 0),
(1993, 68, 'Chialı Ayran', NULL, 3, 1),
(1994, 68, 'Keten Tohumlu Ayran', NULL, 3.5, 1),
(1995, 68, 'Frenk Maydonozlu Ayran', NULL, 3.5, 1),
(1996, 68, 'Soda', NULL, 4, 1),
(1997, 68, 'Su', NULL, 2, 0),
(1998, 68, 'Zencefil Ayran', NULL, 3.5, 0),
(1999, 68, 'Zerdeçallı Ayran', NULL, 3.5, 1),
(2000, 68, 'Naneli Ayran', NULL, 3.5, 1),
(2001, 69, 'Yeşil Detoks (40 cl.)', ' Ispanak, yeşil elma, salatalık, limon, kereviz sapı\r', 10, 1),
(2002, 69, 'Gaji Detoks (40 cl.)', ' Portakal suyu, goji üzümü, muz\r', 12, 1),
(2235, 144, 'hmmm çift kaşarlı', NULL, 15, 1);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `il`
--

DROP TABLE IF EXISTS `il`;
CREATE TABLE `il` (
  `id` tinyint(2) UNSIGNED NOT NULL,
  `il_adi` varchar(50) CHARACTER SET utf8 COLLATE utf8_turkish_ci DEFAULT NULL,
  `slug` varchar(50) CHARACTER SET utf8 COLLATE utf8_turkish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `il`
--

INSERT INTO `il` (`id`, `il_adi`, `slug`) VALUES
(1, 'Adana', 'adana'),
(2, 'Adıyaman', 'adiyaman'),
(3, 'Afyonkarahisar', 'afyonkarahisar'),
(4, 'Ağrı', 'agri'),
(5, 'Amasya', 'amasya'),
(6, 'Ankara', 'ankara'),
(7, 'Antalya', 'antalya'),
(8, 'Artvin', 'artvin'),
(9, 'Aydın', 'aydin'),
(10, 'Balıkesir', 'balikesir'),
(11, 'Bilecik', 'bilecik'),
(12, 'Bingöl', 'bingol'),
(13, 'Bitlis', 'bitlis'),
(14, 'Bolu', 'bolu'),
(15, 'Burdur', 'burdur'),
(16, 'Bursa', 'bursa'),
(17, 'Çanakkale', 'canakkale'),
(18, 'Çankırı', 'cankiri'),
(19, 'Çorum', 'corum'),
(20, 'Denizli', 'denizli'),
(21, 'Diyarbakır', 'diyarbakir'),
(22, 'Edirne', 'edirne'),
(23, 'Elazığ', 'elazig'),
(24, 'Erzincan', 'erzincan'),
(25, 'Erzurum', 'erzurum'),
(26, 'Eskişehir', 'eskisehir'),
(27, 'Gaziantep', 'gaziantep'),
(28, 'Giresun', 'giresun'),
(29, 'Gümüşhane', 'gumushane'),
(30, 'Hakkari', 'hakkari'),
(31, 'Hatay', 'hatay'),
(32, 'Isparta', 'isparta'),
(33, 'Mersin', 'mersin'),
(34, 'İstanbul', 'istanbul'),
(35, 'İzmir', 'izmir'),
(36, 'Kars', 'kars'),
(37, 'Kastamonu', 'kastamonu'),
(38, 'Kayseri', 'kayseri'),
(39, 'Kırklareli', 'kirklareli'),
(40, 'Kırşehir', 'kirsehir'),
(41, 'Kocaeli', 'kocaeli'),
(42, 'Konya', 'konya'),
(43, 'Kütahya', 'kutahya'),
(44, 'Malatya', 'malatya'),
(45, 'Manisa', 'manisa'),
(46, 'Kahramanmaraş', 'kahramanmaras'),
(47, 'Mardin', 'mardin'),
(48, 'Muğla', 'mugla'),
(49, 'Muş', 'mus'),
(50, 'Nevşehir', 'nevsehir'),
(51, 'Niğde', 'nigde'),
(52, 'Ordu', 'ordu'),
(53, 'Rize', 'rize'),
(54, 'Sakarya', 'sakarya'),
(55, 'Samsun', 'samsun'),
(56, 'Siirt', 'siirt'),
(57, 'Sinop', 'sinop'),
(58, 'Sivas', 'sivas'),
(59, 'Tekirdağ', 'tekirdag'),
(60, 'Tokat', 'tokat'),
(61, 'Trabzon', 'trabzon'),
(62, 'Tunceli', 'tunceli'),
(63, 'Şanlıurfa', 'sanliurfa'),
(64, 'Uşak', 'usak'),
(65, 'Van', 'van'),
(66, 'Yozgat', 'yozgat'),
(67, 'Zonguldak', 'zonguldak'),
(68, 'Aksaray', 'aksaray'),
(69, 'Bayburt', 'bayburt'),
(70, 'Karaman', 'karaman'),
(71, 'Kırıkkale', 'kirikkale'),
(72, 'Batman', 'batman'),
(73, 'Şırnak', 'sirnak'),
(74, 'Bartın', 'bartin'),
(75, 'Ardahan', 'ardahan'),
(76, 'Iğdır', 'igdir'),
(77, 'Yalova', 'yalova'),
(78, 'Karabük', 'karabuk'),
(79, 'Kilis', 'kilis'),
(80, 'Osmaniye', 'osmaniye'),
(81, 'Düzce', 'duzce');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `ilce`
--

DROP TABLE IF EXISTS `ilce`;
CREATE TABLE `ilce` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `il_id` tinyint(2) UNSIGNED NOT NULL,
  `ilce_adi` varchar(50) CHARACTER SET utf8 COLLATE utf8_turkish_ci DEFAULT NULL,
  `slug` varchar(50) CHARACTER SET utf8 COLLATE utf8_turkish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `ilce`
--

INSERT INTO `ilce` (`id`, `il_id`, `ilce_adi`, `slug`) VALUES
(1, 1, 'Seyhan', 'seyhan'),
(2, 1, 'Yüreğir', 'yuregir'),
(3, 1, 'Sarıçam', 'saricam'),
(4, 1, 'Çukurova', 'cukurova'),
(5, 1, 'Aladağ(Karsantı)', 'aladagkarsanti'),
(6, 1, 'Ceyhan', 'ceyhan'),
(7, 1, 'Feke', 'feke'),
(8, 1, 'İmamoğlu', 'imamoglu'),
(9, 1, 'Karaisalı', 'karaisali'),
(10, 1, 'Karataş', 'karatas'),
(11, 1, 'Kozan', 'kozan'),
(12, 1, 'Pozantı', 'pozanti'),
(13, 1, 'Saimbeyli', 'saimbeyli'),
(14, 1, 'Tufanbeyli', 'tufanbeyli'),
(15, 1, 'Yumurtalık', 'yumurtalik'),
(16, 2, 'merkez', 'adiyaman'),
(17, 2, 'Besni', 'besni'),
(18, 2, 'Çelikhan', 'celikhan'),
(19, 2, 'Gerger', 'gerger'),
(20, 2, 'Gölbaşı', 'golbasi'),
(21, 2, 'Kahta', 'kahta'),
(22, 2, 'Samsat', 'samsat'),
(23, 2, 'Sincik', 'sincik'),
(24, 2, 'Tut', 'tut'),
(25, 3, 'merkez', 'afyonkarahisar'),
(26, 3, 'Başmakçı', 'basmakci'),
(27, 3, 'Bayat', 'bayat'),
(28, 3, 'Bolvadin', 'bolvadin'),
(29, 3, 'Çay', 'cay'),
(30, 3, 'Çobanlar', 'cobanlar'),
(31, 3, 'Dazkırı', 'dazkiri'),
(32, 3, 'Dinar', 'dinar'),
(33, 3, 'Emirdağ', 'emirdag'),
(34, 3, 'Evciler', 'evciler'),
(35, 3, 'Hocalar', 'hocalar'),
(36, 3, 'İhsaniye', 'ihsaniye'),
(37, 3, 'İscehisar', 'iscehisar'),
(38, 3, 'Kızılören', 'kiziloren'),
(39, 3, 'Sandıklı', 'sandikli'),
(40, 3, 'Sincanlı(Sinanpaşa)', 'sincanlisinanpasa'),
(41, 3, 'Sultandağı', 'sultandagi'),
(42, 3, 'Şuhut', 'suhut'),
(43, 4, 'merkez', 'agri'),
(44, 4, 'Diyadin', 'diyadin'),
(45, 4, 'Doğubeyazıt', 'dogubeyazit'),
(46, 4, 'Eleşkirt', 'eleskirt'),
(47, 4, 'Hamur', 'hamur'),
(48, 4, 'Patnos', 'patnos'),
(49, 4, 'Taşlıçay', 'taslicay'),
(50, 4, 'Tutak', 'tutak'),
(51, 5, 'merkez', 'amasya'),
(52, 5, 'Göynücek', 'goynucek'),
(53, 5, 'Gümüşhacıköy', 'gumushacikoy'),
(54, 5, 'Hamamözü', 'hamamozu'),
(55, 5, 'Merzifon', 'merzifon'),
(56, 5, 'Suluova', 'suluova'),
(57, 5, 'Taşova', 'tasova'),
(58, 6, 'Altındağ', 'altindag'),
(59, 6, 'Çankaya', 'cankaya'),
(60, 6, 'Etimesgut', 'etimesgut'),
(61, 6, 'Keçiören', 'kecioren'),
(62, 6, 'Mamak', 'mamak'),
(63, 6, 'Sincan', 'sincan'),
(64, 6, 'Yenimahalle', 'yenimahalle'),
(65, 6, 'Gölbaşı', 'golbasi'),
(66, 6, 'Pursaklar', 'pursaklar'),
(67, 6, 'Akyurt', 'akyurt'),
(68, 6, 'Ayaş', 'ayas'),
(69, 6, 'Bala', 'bala'),
(70, 6, 'Beypazarı', 'beypazari'),
(71, 6, 'Çamlıdere', 'camlidere'),
(72, 6, 'Çubuk', 'cubuk'),
(73, 6, 'Elmadağ', 'elmadag'),
(74, 6, 'Evren', 'evren'),
(75, 6, 'Güdül', 'gudul'),
(76, 6, 'Haymana', 'haymana'),
(77, 6, 'Kalecik', 'kalecik'),
(78, 6, 'Kazan', 'kazan'),
(79, 6, 'Kızılcahamam', 'kizilcahamam'),
(80, 6, 'Nallıhan', 'nallihan'),
(81, 6, 'Polatlı', 'polatli'),
(82, 6, 'Şereflikoçhisar', 'sereflikochisar'),
(83, 7, 'Muratpaşa', 'muratpasa'),
(84, 7, 'Kepez', 'kepez'),
(85, 7, 'Konyaaltı', 'konyaalti'),
(86, 7, 'Aksu', 'aksu'),
(87, 7, 'Döşemealtı', 'dosemealti'),
(88, 7, 'Akseki', 'akseki'),
(89, 7, 'Alanya', 'alanya'),
(90, 7, 'Elmalı', 'elmali'),
(91, 7, 'Finike', 'finike'),
(92, 7, 'Gazipaşa', 'gazipasa'),
(93, 7, 'Gündoğmuş', 'gundogmus'),
(94, 7, 'İbradı(Aydınkent)', 'ibradiaydinkent'),
(95, 7, 'Kale(Demre)', 'kaledemre'),
(96, 7, 'Kaş', 'kas'),
(97, 7, 'Kemer', 'kemer'),
(98, 7, 'Korkuteli', 'korkuteli'),
(99, 7, 'Kumluca', 'kumluca'),
(100, 7, 'Manavgat', 'manavgat'),
(101, 7, 'Serik', 'serik'),
(102, 8, 'merkez', 'artvin'),
(103, 8, 'Ardanuç', 'ardanuc'),
(104, 8, 'Arhavi', 'arhavi'),
(105, 8, 'Borçka', 'borcka'),
(106, 8, 'Hopa', 'hopa'),
(107, 8, 'Murgul(Göktaş)', 'murgulgoktas'),
(108, 8, 'Şavşat', 'savsat'),
(109, 8, 'Yusufeli', 'yusufeli'),
(110, 9, 'merkez', 'aydin'),
(111, 9, 'Bozdoğan', 'bozdogan'),
(112, 9, 'Buharkent(Çubukdağı)', 'buharkentcubukdagi'),
(113, 9, 'Çine', 'cine'),
(114, 9, 'Germencik', 'germencik'),
(115, 9, 'İncirliova', 'incirliova'),
(116, 9, 'Karacasu', 'karacasu'),
(117, 9, 'Karpuzlu', 'karpuzlu'),
(118, 9, 'Koçarlı', 'kocarli'),
(119, 9, 'Köşk', 'kosk'),
(120, 9, 'Kuşadası', 'kusadasi'),
(121, 9, 'Kuyucak', 'kuyucak'),
(122, 9, 'Nazilli', 'nazilli'),
(123, 9, 'Söke', 'soke'),
(124, 9, 'Sultanhisar', 'sultanhisar'),
(125, 9, 'Didim', 'didimyenihisar'),
(126, 9, 'Yenipazar', 'yenipazar'),
(127, 10, 'merkez', 'balikesir'),
(128, 10, 'Ayvalık', 'ayvalik'),
(129, 10, 'Balya', 'balya'),
(130, 10, 'Bandırma', 'bandirma'),
(131, 10, 'Bigadiç', 'bigadic'),
(132, 10, 'Burhaniye', 'burhaniye'),
(133, 10, 'Dursunbey', 'dursunbey'),
(134, 10, 'Edremit', 'edremit'),
(135, 10, 'Erdek', 'erdek'),
(136, 10, 'Gömeç', 'gomec'),
(137, 10, 'Gönen', 'gonen'),
(138, 10, 'Havran', 'havran'),
(139, 10, 'İvrindi', 'ivrindi'),
(140, 10, 'Kepsut', 'kepsut'),
(141, 10, 'Manyas', 'manyas'),
(142, 10, 'Marmara', 'marmara'),
(143, 10, 'Savaştepe', 'savastepe'),
(144, 10, 'Sındırgı', 'sindirgi'),
(145, 10, 'Susurluk', 'susurluk'),
(146, 11, 'merkez', 'bilecik'),
(147, 11, 'Bozüyük', 'bozuyuk'),
(148, 11, 'Gölpazarı', 'golpazari'),
(149, 11, 'İnhisar', 'inhisar'),
(150, 11, 'Osmaneli', 'osmaneli'),
(151, 11, 'Pazaryeri', 'pazaryeri'),
(152, 11, 'Söğüt', 'sogut'),
(153, 11, 'Yenipazar', 'yenipazar'),
(154, 12, 'merkez', 'bingol'),
(155, 12, 'Adaklı', 'adakli'),
(156, 12, 'Genç', 'genc'),
(157, 12, 'Karlıova', 'karliova'),
(158, 12, 'Kığı', 'kigi'),
(159, 12, 'Solhan', 'solhan'),
(160, 12, 'Yayladere', 'yayladere'),
(161, 12, 'Yedisu', 'yedisu'),
(162, 13, 'merkez', 'bitlis'),
(163, 13, 'Adilcevaz', 'adilcevaz'),
(164, 13, 'Ahlat', 'ahlat'),
(165, 13, 'Güroymak', 'guroymak'),
(166, 13, 'Hizan', 'hizan'),
(167, 13, 'Mutki', 'mutki'),
(168, 13, 'Tatvan', 'tatvan'),
(169, 14, 'merkez', 'bolu'),
(170, 14, 'Dörtdivan', 'dortdivan'),
(171, 14, 'Gerede', 'gerede'),
(172, 14, 'Göynük', 'goynuk'),
(173, 14, 'Kıbrıscık', 'kibriscik'),
(174, 14, 'Mengen', 'mengen'),
(175, 14, 'Mudurnu', 'mudurnu'),
(176, 14, 'Seben', 'seben'),
(177, 14, 'Yeniçağa', 'yenicaga'),
(178, 15, 'merkez', 'burdur'),
(179, 15, 'Ağlasun', 'aglasun'),
(180, 15, 'Altınyayla(Dirmil)', 'altinyayladirmil'),
(181, 15, 'Bucak', 'bucak'),
(182, 15, 'Çavdır', 'cavdir'),
(183, 15, 'Çeltikçi', 'celtikci'),
(184, 15, 'Gölhisar', 'golhisar'),
(185, 15, 'Karamanlı', 'karamanli'),
(186, 15, 'Kemer', 'kemer'),
(187, 15, 'Tefenni', 'tefenni'),
(188, 15, 'Yeşilova', 'yesilova'),
(189, 16, 'Nilüfer', 'nilufer'),
(190, 16, 'Osmangazi', 'osmangazi'),
(191, 16, 'Yıldırım', 'yildirim'),
(192, 16, 'Büyükorhan', 'buyukorhan'),
(193, 16, 'Gemlik', 'gemlik'),
(194, 16, 'Gürsu', 'gursu'),
(195, 16, 'Harmancık', 'harmancik'),
(196, 16, 'İnegöl', 'inegol'),
(197, 16, 'İznik', 'iznik'),
(198, 16, 'Karacabey', 'karacabey'),
(199, 16, 'Keles', 'keles'),
(200, 16, 'Kestel', 'kestel'),
(201, 16, 'Mudanya', 'mudanya'),
(202, 16, 'Mustafakemalpaşa', 'mustafakemalpasa'),
(203, 16, 'Orhaneli', 'orhaneli'),
(204, 16, 'Orhangazi', 'orhangazi'),
(205, 16, 'Yenişehir', 'yenisehir'),
(206, 17, 'merkez', 'canakkale'),
(207, 17, 'Ayvacık-Assos', 'ayvacik-assos'),
(208, 17, 'Bayramiç', 'bayramic'),
(209, 17, 'Biga', 'biga'),
(210, 17, 'Bozcaada', 'bozcaada'),
(211, 17, 'Çan', 'can'),
(212, 17, 'Eceabat', 'eceabat'),
(213, 17, 'Ezine', 'ezine'),
(214, 17, 'Gelibolu', 'gelibolu'),
(215, 17, 'Gökçeada(İmroz)', 'gokceadaimroz'),
(216, 17, 'Lapseki', 'lapseki'),
(217, 17, 'Yenice', 'yenice'),
(218, 18, 'merkez', 'cankiri'),
(219, 18, 'Atkaracalar', 'atkaracalar'),
(220, 18, 'Bayramören', 'bayramoren'),
(221, 18, 'Çerkeş', 'cerkes'),
(222, 18, 'Eldivan', 'eldivan'),
(223, 18, 'Ilgaz', 'ilgaz'),
(224, 18, 'Kızılırmak', 'kizilirmak'),
(225, 18, 'Korgun', 'korgun'),
(226, 18, 'Kurşunlu', 'kursunlu'),
(227, 18, 'Orta', 'orta'),
(228, 18, 'Şabanözü', 'sabanozu'),
(229, 18, 'Yapraklı', 'yaprakli'),
(230, 19, 'merkez', 'corum'),
(231, 19, 'Alaca', 'alaca'),
(232, 19, 'Bayat', 'bayat'),
(233, 19, 'Boğazkale', 'bogazkale'),
(234, 19, 'Dodurga', 'dodurga'),
(235, 19, 'İskilip', 'iskilip'),
(236, 19, 'Kargı', 'kargi'),
(237, 19, 'Laçin', 'lacin'),
(238, 19, 'Mecitözü', 'mecitozu'),
(239, 19, 'Oğuzlar(Karaören)', 'oguzlarkaraoren'),
(240, 19, 'Ortaköy', 'ortakoy'),
(241, 19, 'Osmancık', 'osmancik'),
(242, 19, 'Sungurlu', 'sungurlu'),
(243, 19, 'Uğurludağ', 'ugurludag'),
(244, 20, 'merkez', 'denizli'),
(245, 20, 'Acıpayam', 'acipayam'),
(246, 20, 'Akköy', 'akkoy'),
(247, 20, 'Babadağ', 'babadag'),
(248, 20, 'Baklan', 'baklan'),
(249, 20, 'Bekilli', 'bekilli'),
(250, 20, 'Beyağaç', 'beyagac'),
(251, 20, 'Bozkurt', 'bozkurt'),
(252, 20, 'Buldan', 'buldan'),
(253, 20, 'Çal', 'cal'),
(254, 20, 'Çameli', 'cameli'),
(255, 20, 'Çardak', 'cardak'),
(256, 20, 'Çivril', 'civril'),
(257, 20, 'Güney', 'guney'),
(258, 20, 'Honaz', 'honaz'),
(259, 20, 'Kale', 'kale'),
(260, 20, 'Sarayköy', 'saraykoy'),
(261, 20, 'Serinhisar', 'serinhisar'),
(262, 20, 'Tavas', 'tavas'),
(263, 21, 'Sur', 'sur'),
(264, 21, 'Bağlar', 'baglar'),
(265, 21, 'Yenişehir', 'yenisehir'),
(266, 21, 'Kayapınar', 'kayapinar'),
(267, 21, 'Bismil', 'bismil'),
(268, 21, 'Çermik', 'cermik'),
(269, 21, 'Çınar', 'cinar'),
(270, 21, 'Çüngüş', 'cungus'),
(271, 21, 'Dicle', 'dicle'),
(272, 21, 'Eğil', 'egil'),
(273, 21, 'Ergani', 'ergani'),
(274, 21, 'Hani', 'hani'),
(275, 21, 'Hazro', 'hazro'),
(276, 21, 'Kocaköy', 'kocakoy'),
(277, 21, 'Kulp', 'kulp'),
(278, 21, 'Lice', 'lice'),
(279, 21, 'Silvan', 'silvan'),
(280, 22, 'merkez', 'edirne'),
(281, 22, 'Enez', 'enez'),
(282, 22, 'Havsa', 'havsa'),
(283, 22, 'İpsala', 'ipsala'),
(284, 22, 'Keşan', 'kesan'),
(285, 22, 'Lalapaşa', 'lalapasa'),
(286, 22, 'Meriç', 'meric'),
(287, 22, 'Süleoğlu(Süloğlu)', 'suleoglusuloglu'),
(288, 22, 'Uzunköprü', 'uzunkopru'),
(289, 23, 'merkez', 'elazig'),
(290, 23, 'Ağın', 'agin'),
(291, 23, 'Alacakaya', 'alacakaya'),
(292, 23, 'Arıcak', 'aricak'),
(293, 23, 'Baskil', 'baskil'),
(294, 23, 'Karakoçan', 'karakocan'),
(295, 23, 'Keban', 'keban'),
(296, 23, 'Kovancılar', 'kovancilar'),
(297, 23, 'Maden', 'maden'),
(298, 23, 'Palu', 'palu'),
(299, 23, 'Sivrice', 'sivrice'),
(300, 24, 'merkez', 'erzincan'),
(301, 24, 'Çayırlı', 'cayirli'),
(302, 24, 'İliç(Ilıç)', 'ilicilic'),
(303, 24, 'Kemah', 'kemah'),
(304, 24, 'Kemaliye', 'kemaliye'),
(305, 24, 'Otlukbeli', 'otlukbeli'),
(306, 24, 'Refahiye', 'refahiye'),
(307, 24, 'Tercan', 'tercan'),
(308, 24, 'Üzümlü', 'uzumlu'),
(309, 25, 'Palandöken', 'palandoken'),
(310, 25, 'Yakutiye', 'yakutiye'),
(311, 25, 'Aziziye(Ilıca)', 'aziziyeilica'),
(312, 25, 'Aşkale', 'askale'),
(313, 25, 'Çat', 'cat'),
(314, 25, 'Hınıs', 'hinis'),
(315, 25, 'Horasan', 'horasan'),
(316, 25, 'İspir', 'ispir'),
(317, 25, 'Karaçoban', 'karacoban'),
(318, 25, 'Karayazı', 'karayazi'),
(319, 25, 'Köprüköy', 'koprukoy'),
(320, 25, 'Narman', 'narman'),
(321, 25, 'Oltu', 'oltu'),
(322, 25, 'Olur', 'olur'),
(323, 25, 'Pasinler', 'pasinler'),
(324, 25, 'Pazaryolu', 'pazaryolu'),
(325, 25, 'Şenkaya', 'senkaya'),
(326, 25, 'Tekman', 'tekman'),
(327, 25, 'Tortum', 'tortum'),
(328, 25, 'Uzundere', 'uzundere'),
(329, 26, 'Odunpazarı', 'odunpazari'),
(330, 26, 'Tepebaşı', 'tepebasi'),
(331, 26, 'Alpu', 'alpu'),
(332, 26, 'Beylikova', 'beylikova'),
(333, 26, 'Çifteler', 'cifteler'),
(334, 26, 'Günyüzü', 'gunyuzu'),
(335, 26, 'Han', 'han'),
(336, 26, 'İnönü', 'inonu'),
(337, 26, 'Mahmudiye', 'mahmudiye'),
(338, 26, 'Mihalgazi', 'mihalgazi'),
(339, 26, 'Mihalıçcık', 'mihaliccik'),
(340, 26, 'Sarıcakaya', 'saricakaya'),
(341, 26, 'Seyitgazi', 'seyitgazi'),
(342, 26, 'Sivrihisar', 'sivrihisar'),
(343, 27, 'Şahinbey', 'sahinbey'),
(344, 27, 'Şehitkamil', 'sehitkamil'),
(345, 27, 'Oğuzeli', 'oguzeli'),
(346, 27, 'Araban', 'araban'),
(347, 27, 'İslahiye', 'islahiye'),
(348, 27, 'Karkamış', 'karkamis'),
(349, 27, 'Nizip', 'nizip'),
(350, 27, 'Nurdağı', 'nurdagi'),
(351, 27, 'Yavuzeli', 'yavuzeli'),
(352, 28, 'merkez', 'giresun'),
(353, 28, 'Alucra', 'alucra'),
(354, 28, 'Bulancak', 'bulancak'),
(355, 28, 'Çamoluk', 'camoluk'),
(356, 28, 'Çanakçı', 'canakci'),
(357, 28, 'Dereli', 'dereli'),
(358, 28, 'Doğankent', 'dogankent'),
(359, 28, 'Espiye', 'espiye'),
(360, 28, 'Eynesil', 'eynesil'),
(361, 28, 'Görele', 'gorele'),
(362, 28, 'Güce', 'guce'),
(363, 28, 'Keşap', 'kesap'),
(364, 28, 'Piraziz', 'piraziz'),
(365, 28, 'Şebinkarahisar', 'sebinkarahisar'),
(366, 28, 'Tirebolu', 'tirebolu'),
(367, 28, 'Yağlıdere', 'yaglidere'),
(368, 29, 'merkez', 'gumushane'),
(369, 29, 'Kelkit', 'kelkit'),
(370, 29, 'Köse', 'kose'),
(371, 29, 'Kürtün', 'kurtun'),
(372, 29, 'Şiran', 'siran'),
(373, 29, 'Torul', 'torul'),
(374, 30, 'merkez', 'hakkari'),
(375, 30, 'Çukurca', 'cukurca'),
(376, 30, 'Şemdinli', 'semdinli'),
(377, 30, 'Yüksekova', 'yuksekova'),
(378, 31, 'Antakya', 'antakya'),
(379, 31, 'Altınözü', 'altinozu'),
(380, 31, 'Belen', 'belen'),
(381, 31, 'Dörtyol', 'dortyol'),
(382, 31, 'Erzin', 'erzin'),
(383, 31, 'Hassa', 'hassa'),
(384, 31, 'İskenderun', 'iskenderun'),
(385, 31, 'Kırıkhan', 'kirikhan'),
(386, 31, 'Kumlu', 'kumlu'),
(387, 31, 'Reyhanlı', 'reyhanli'),
(388, 31, 'Samandağ', 'samandag'),
(389, 31, 'Yayladağı', 'yayladagi'),
(390, 32, 'merkez', 'isparta'),
(391, 32, 'Aksu', 'aksu'),
(392, 32, 'Atabey', 'atabey'),
(393, 32, 'Eğridir(Eğirdir)', 'egridiregirdir'),
(394, 32, 'Gelendost', 'gelendost'),
(395, 32, 'Gönen', 'gonen'),
(396, 32, 'Keçiborlu', 'keciborlu'),
(397, 32, 'Senirkent', 'senirkent'),
(398, 32, 'Sütçüler', 'sutculer'),
(399, 32, 'Şarkikaraağaç', 'sarkikaraagac'),
(400, 32, 'Uluborlu', 'uluborlu'),
(401, 32, 'Yalvaç', 'yalvac'),
(402, 32, 'Yenişarbademli', 'yenisarbademli'),
(403, 33, 'Akdeniz', 'akdeniz'),
(404, 33, 'Yenişehir', 'yenisehir'),
(405, 33, 'Toroslar', 'toroslar'),
(406, 33, 'Mezitli', 'mezitli'),
(407, 33, 'Anamur', 'anamur'),
(408, 33, 'Aydıncık', 'aydincik'),
(409, 33, 'Bozyazı', 'bozyazi'),
(410, 33, 'Çamlıyayla', 'camliyayla'),
(411, 33, 'Erdemli', 'erdemli'),
(412, 33, 'Gülnar(Gülpınar)', 'gulnargulpinar'),
(413, 33, 'Mut', 'mut'),
(414, 33, 'Silifke', 'silifke'),
(415, 33, 'Tarsus', 'tarsus'),
(416, 34, 'Bakırköy', 'bakirkoy'),
(417, 34, 'Bayrampaşa', 'bayrampasa'),
(418, 34, 'Beşiktaş', 'besiktas'),
(419, 34, 'Beyoğlu', 'beyoglu'),
(420, 34, 'Arnavutköy', 'arnavutkoy'),
(421, 34, 'Eyüp', 'eyup'),
(422, 34, 'Fatih', 'fatih'),
(423, 34, 'Gaziosmanpaşa', 'gaziosmanpasa'),
(424, 34, 'Kağıthane', 'kagithane'),
(425, 34, 'Küçükçekmece', 'kucukcekmece'),
(426, 34, 'Sarıyer', 'sariyer'),
(427, 34, 'Şişli', 'sisli'),
(428, 34, 'Zeytinburnu', 'zeytinburnu'),
(429, 34, 'Avcılar', 'avcilar'),
(430, 34, 'Güngören', 'gungoren'),
(431, 34, 'Bahçelievler', 'bahcelievler'),
(432, 34, 'Bağcılar', 'bagcilar'),
(433, 34, 'Esenler', 'esenler'),
(434, 34, 'Başakşehir', 'basaksehir'),
(435, 34, 'Beylikdüzü', 'beylikduzu'),
(436, 34, 'Esenyurt', 'esenyurt'),
(437, 34, 'Sultangazi', 'sultangazi'),
(438, 34, 'Adalar', 'adalar'),
(439, 34, 'Beykoz', 'beykoz'),
(440, 34, 'Kadıköy', 'kadikoy'),
(441, 34, 'Kartal', 'kartal'),
(442, 34, 'Pendik', 'pendik'),
(443, 34, 'Ümraniye', 'umraniye'),
(444, 34, 'Üsküdar', 'uskudar'),
(445, 34, 'Tuzla', 'tuzla'),
(446, 34, 'Maltepe', 'maltepe'),
(447, 34, 'Ataşehir', 'atasehir'),
(448, 34, 'Çekmeköy', 'cekmekoy'),
(449, 34, 'Sancaktepe', 'sancaktepe'),
(450, 34, 'Büyükçekmece', 'buyukcekmece'),
(451, 34, 'Çatalca', 'catalca'),
(452, 34, 'Silivri', 'silivri'),
(453, 34, 'Şile', 'sile'),
(454, 34, 'Sultanbeyli', 'sultanbeyli'),
(455, 35, 'Aliağa', 'aliaga'),
(456, 35, 'Balçova', 'balcova'),
(457, 35, 'Bayındır', 'bayindir'),
(458, 35, 'Bornova', 'bornova'),
(459, 35, 'Buca', 'buca'),
(460, 35, 'Çiğli', 'cigli'),
(461, 35, 'Foça', 'foca'),
(462, 35, 'Gaziemir', 'gaziemir'),
(463, 35, 'Güzelbahçe', 'guzelbahce'),
(464, 35, 'Karşıyaka', 'karsiyaka'),
(465, 35, 'Kemalpaşa', 'kemalpasa'),
(466, 35, 'Konak', 'konak'),
(467, 35, 'Cumaovası(Menderes)', 'cumaovasimenderes'),
(468, 35, 'Menemen', 'menemen'),
(469, 35, 'Narlıdere', 'narlidere'),
(470, 35, 'Seferihisar', 'seferihisar'),
(471, 35, 'Selçuk', 'selcuk'),
(472, 35, 'Torbalı', 'torbali'),
(473, 35, 'Urla', 'urla'),
(474, 35, 'Bayraklı', 'bayrakli'),
(475, 35, 'Karabağlar', 'karabaglar'),
(476, 35, 'Bergama', 'bergama'),
(477, 35, 'Beydağ', 'beydag'),
(478, 35, 'Çeşme', 'cesme'),
(479, 35, 'Dikili', 'dikili'),
(480, 35, 'Karaburun', 'karaburun'),
(481, 35, 'Kınık', 'kinik'),
(482, 35, 'Kiraz', 'kiraz'),
(483, 35, 'Ödemiş', 'odemis'),
(484, 35, 'Tire', 'tire'),
(485, 36, 'merkez', 'kars'),
(486, 36, 'Akyaka', 'akyaka'),
(487, 36, 'Arpaçay', 'arpacay'),
(488, 36, 'Digor', 'digor'),
(489, 36, 'Kağızman', 'kagizman'),
(490, 36, 'Sarıkamış', 'sarikamis'),
(491, 36, 'Selim', 'selim'),
(492, 36, 'Susuz', 'susuz'),
(493, 37, 'merkez', 'kastamonu'),
(494, 37, 'Abana', 'abana'),
(495, 37, 'Ağlı', 'agli'),
(496, 37, 'Araç', 'arac'),
(497, 37, 'Azdavay', 'azdavay'),
(498, 37, 'Bozkurt', 'bozkurt'),
(499, 37, 'Cide', 'cide'),
(500, 37, 'Çatalzeytin', 'catalzeytin'),
(501, 37, 'Daday', 'daday'),
(502, 37, 'Devrekani', 'devrekani'),
(503, 37, 'Doğanyurt', 'doganyurt'),
(504, 37, 'Hanönü(Gökçeağaç)', 'hanonugokceagac'),
(505, 37, 'İhsangazi', 'ihsangazi'),
(506, 37, 'İnebolu', 'inebolu'),
(507, 37, 'Küre', 'kure'),
(508, 37, 'Pınarbaşı', 'pinarbasi'),
(509, 37, 'Seydiler', 'seydiler'),
(510, 37, 'Şenpazar', 'senpazar'),
(511, 37, 'Taşköprü', 'taskopru'),
(512, 37, 'Tosya', 'tosya'),
(513, 38, 'Kocasinan', 'kocasinan'),
(514, 38, 'Melikgazi', 'melikgazi'),
(515, 38, 'Talas', 'talas'),
(516, 38, 'Akkışla', 'akkisla'),
(517, 38, 'Bünyan', 'bunyan'),
(518, 38, 'Develi', 'develi'),
(519, 38, 'Felahiye', 'felahiye'),
(520, 38, 'Hacılar', 'hacilar'),
(521, 38, 'İncesu', 'incesu'),
(522, 38, 'Özvatan(Çukur)', 'ozvatancukur'),
(523, 38, 'Pınarbaşı', 'pinarbasi'),
(524, 38, 'Sarıoğlan', 'sarioglan'),
(525, 38, 'Sarız', 'sariz'),
(526, 38, 'Tomarza', 'tomarza'),
(527, 38, 'Yahyalı', 'yahyali'),
(528, 38, 'Yeşilhisar', 'yesilhisar'),
(529, 39, 'merkez', 'kirklareli'),
(530, 39, 'Babaeski', 'babaeski'),
(531, 39, 'Demirköy', 'demirkoy'),
(532, 39, 'Kofçaz', 'kofcaz'),
(533, 39, 'Lüleburgaz', 'luleburgaz'),
(534, 39, 'Pehlivanköy', 'pehlivankoy'),
(535, 39, 'Pınarhisar', 'pinarhisar'),
(536, 39, 'Vize', 'vize'),
(537, 40, 'merkez', 'kirsehir'),
(538, 40, 'Akçakent', 'akcakent'),
(539, 40, 'Akpınar', 'akpinar'),
(540, 40, 'Boztepe', 'boztepe'),
(541, 40, 'Çiçekdağı', 'cicekdagi'),
(542, 40, 'Kaman', 'kaman'),
(543, 40, 'Mucur', 'mucur'),
(544, 41, 'İzmit', 'izmit'),
(545, 41, 'Başiskele', 'basiskele'),
(546, 41, 'Çayırova', 'cayirova'),
(547, 41, 'Darıca', 'darica'),
(548, 41, 'Dilovası', 'dilovasi'),
(549, 41, 'Kartepe', 'kartepe'),
(550, 41, 'Gebze', 'gebze'),
(551, 41, 'Gölcük', 'golcuk'),
(552, 41, 'Kandıra', 'kandira'),
(553, 41, 'Karamürsel', 'karamursel'),
(554, 41, 'Tütünçiftlik', 'tutunciftlik'),
(555, 41, 'Derince', 'derince'),
(556, 42, 'Karatay', 'karatay'),
(557, 42, 'Meram', 'meram'),
(558, 42, 'Selçuklu', 'selcuklu'),
(559, 42, 'Ahırlı', 'ahirli'),
(560, 42, 'Akören', 'akoren'),
(561, 42, 'Akşehir', 'aksehir'),
(562, 42, 'Altınekin', 'altinekin'),
(563, 42, 'Beyşehir', 'beysehir'),
(564, 42, 'Bozkır', 'bozkir'),
(565, 42, 'Cihanbeyli', 'cihanbeyli'),
(566, 42, 'Çeltik', 'celtik'),
(567, 42, 'Çumra', 'cumra'),
(568, 42, 'Derbent', 'derbent'),
(569, 42, 'Derebucak', 'derebucak'),
(570, 42, 'Doğanhisar', 'doganhisar'),
(571, 42, 'Emirgazi', 'emirgazi'),
(572, 42, 'Ereğli', 'eregli'),
(573, 42, 'Güneysınır', 'guneysinir'),
(574, 42, 'Hadim', 'hadim'),
(575, 42, 'Halkapınar', 'halkapinar'),
(576, 42, 'Hüyük', 'huyuk'),
(577, 42, 'Ilgın', 'ilgin'),
(578, 42, 'Kadınhanı', 'kadinhani'),
(579, 42, 'Karapınar', 'karapinar'),
(580, 42, 'Kulu', 'kulu'),
(581, 42, 'Sarayönü', 'sarayonu'),
(582, 42, 'Seydişehir', 'seydisehir'),
(583, 42, 'Taşkent', 'taskent'),
(584, 42, 'Tuzlukçu', 'tuzlukcu'),
(585, 42, 'Yalıhüyük', 'yalihuyuk'),
(586, 42, 'Yunak', 'yunak'),
(587, 43, 'merkez', 'kutahya'),
(588, 43, 'Altıntaş', 'altintas'),
(589, 43, 'Aslanapa', 'aslanapa'),
(590, 43, 'Çavdarhisar', 'cavdarhisar'),
(591, 43, 'Domaniç', 'domanic'),
(592, 43, 'Dumlupınar', 'dumlupinar'),
(593, 43, 'Emet', 'emet'),
(594, 43, 'Gediz', 'gediz'),
(595, 43, 'Hisarcık', 'hisarcik'),
(596, 43, 'Pazarlar', 'pazarlar'),
(597, 43, 'Simav', 'simav'),
(598, 43, 'Şaphane', 'saphane'),
(599, 43, 'Tavşanlı', 'tavsanli'),
(600, 43, 'Tunçbilek', 'tuncbilek'),
(601, 44, 'merkez', 'malatya'),
(602, 44, 'Akçadağ', 'akcadag'),
(603, 44, 'Arapkir', 'arapkir'),
(604, 44, 'Arguvan', 'arguvan'),
(605, 44, 'Battalgazi', 'battalgazi'),
(606, 44, 'Darende', 'darende'),
(607, 44, 'Doğanşehir', 'dogansehir'),
(608, 44, 'Doğanyol', 'doganyol'),
(609, 44, 'Hekimhan', 'hekimhan'),
(610, 44, 'Kale', 'kale'),
(611, 44, 'Kuluncak', 'kuluncak'),
(612, 44, 'Pötürge', 'poturge'),
(613, 44, 'Yazıhan', 'yazihan'),
(614, 44, 'Yeşilyurt', 'yesilyurt'),
(615, 45, 'merkez', 'manisa'),
(616, 45, 'Ahmetli', 'ahmetli'),
(617, 45, 'Akhisar', 'akhisar'),
(618, 45, 'Alaşehir', 'alasehir'),
(619, 45, 'Demirci', 'demirci'),
(620, 45, 'Gölmarmara', 'golmarmara'),
(621, 45, 'Gördes', 'gordes'),
(622, 45, 'Kırkağaç', 'kirkagac'),
(623, 45, 'Köprübaşı', 'koprubasi'),
(624, 45, 'Kula', 'kula'),
(625, 45, 'Salihli', 'salihli'),
(626, 45, 'Sarıgöl', 'sarigol'),
(627, 45, 'Saruhanlı', 'saruhanli'),
(628, 45, 'Selendi', 'selendi'),
(629, 45, 'Soma', 'soma'),
(630, 45, 'Turgutlu', 'turgutlu'),
(631, 46, 'merkez', 'kahramanmaras'),
(632, 46, 'Afşin', 'afsin'),
(633, 46, 'Andırın', 'andirin'),
(634, 46, 'Çağlayancerit', 'caglayancerit'),
(635, 46, 'Ekinözü', 'ekinozu'),
(636, 46, 'Elbistan', 'elbistan'),
(637, 46, 'Göksun', 'goksun'),
(638, 46, 'Nurhak', 'nurhak'),
(639, 46, 'Pazarcık', 'pazarcik'),
(640, 46, 'Türkoğlu', 'turkoglu'),
(641, 47, 'merkez', 'mardin'),
(642, 47, 'Dargeçit', 'dargecit'),
(643, 47, 'Derik', 'derik'),
(644, 47, 'Kızıltepe', 'kiziltepe'),
(645, 47, 'Mazıdağı', 'mazidagi'),
(646, 47, 'Midyat(Estel)', 'midyatestel'),
(647, 47, 'Nusaybin', 'nusaybin'),
(648, 47, 'Ömerli', 'omerli'),
(649, 47, 'Savur', 'savur'),
(650, 47, 'Yeşilli', 'yesilli'),
(651, 48, 'merkez', 'mugla'),
(652, 48, 'Bodrum', 'bodrum'),
(653, 48, 'Dalaman', 'dalaman'),
(654, 48, 'Datça', 'datca'),
(655, 48, 'Fethiye', 'fethiye'),
(656, 48, 'Kavaklıdere', 'kavaklidere'),
(657, 48, 'Köyceğiz', 'koycegiz'),
(658, 48, 'Marmaris', 'marmaris'),
(659, 48, 'Milas', 'milas'),
(660, 48, 'Ortaca', 'ortaca'),
(661, 48, 'Ula', 'ula'),
(662, 48, 'Yatağan', 'yatagan'),
(663, 49, 'merkez', 'mus'),
(664, 49, 'Bulanık', 'bulanik'),
(665, 49, 'Hasköy', 'haskoy'),
(666, 49, 'Korkut', 'korkut'),
(667, 49, 'Malazgirt', 'malazgirt'),
(668, 49, 'Varto', 'varto'),
(669, 50, 'merkez', 'nevsehir'),
(670, 50, 'Acıgöl', 'acigol'),
(671, 50, 'Avanos', 'avanos'),
(672, 50, 'Derinkuyu', 'derinkuyu'),
(673, 50, 'Gülşehir', 'gulsehir'),
(674, 50, 'Hacıbektaş', 'hacibektas'),
(675, 50, 'Kozaklı', 'kozakli'),
(676, 50, 'Göreme', 'goreme'),
(677, 51, 'merkez', 'nigde'),
(678, 51, 'Altunhisar', 'altunhisar'),
(679, 51, 'Bor', 'bor'),
(680, 51, 'Çamardı', 'camardi'),
(681, 51, 'Çiftlik(Özyurt)', 'ciftlikozyurt'),
(682, 51, 'Ulukışla', 'ulukisla'),
(683, 52, 'merkez', 'ordu'),
(684, 52, 'Akkuş', 'akkus'),
(685, 52, 'Aybastı', 'aybasti'),
(686, 52, 'Çamaş', 'camas'),
(687, 52, 'Çatalpınar', 'catalpinar'),
(688, 52, 'Çaybaşı', 'caybasi'),
(689, 52, 'Fatsa', 'fatsa'),
(690, 52, 'Gölköy', 'golkoy'),
(691, 52, 'Gülyalı', 'gulyali'),
(692, 52, 'Gürgentepe', 'gurgentepe'),
(693, 52, 'İkizce', 'ikizce'),
(694, 52, 'Karadüz(Kabadüz)', 'karaduzkabaduz'),
(695, 52, 'Kabataş', 'kabatas'),
(696, 52, 'Korgan', 'korgan'),
(697, 52, 'Kumru', 'kumru'),
(698, 52, 'Mesudiye', 'mesudiye'),
(699, 52, 'Perşembe', 'persembe'),
(700, 52, 'Ulubey', 'ulubey'),
(701, 52, 'Ünye', 'unye'),
(702, 53, 'merkez', 'rize'),
(703, 53, 'Ardeşen', 'ardesen'),
(704, 53, 'Çamlıhemşin', 'camlihemsin'),
(705, 53, 'Çayeli', 'cayeli'),
(706, 53, 'Derepazarı', 'derepazari'),
(707, 53, 'Fındıklı', 'findikli'),
(708, 53, 'Güneysu', 'guneysu'),
(709, 53, 'Hemşin', 'hemsin'),
(710, 53, 'İkizdere', 'ikizdere'),
(711, 53, 'İyidere', 'iyidere'),
(712, 53, 'Kalkandere', 'kalkandere'),
(713, 53, 'Pazar', 'pazar'),
(714, 54, 'Adapazarı', 'adapazari'),
(715, 54, 'Hendek', 'hendek'),
(716, 54, 'Arifiye', 'arifiye'),
(717, 54, 'Erenler', 'erenler'),
(718, 54, 'Serdivan', 'serdivan'),
(719, 54, 'Akyazı', 'akyazi'),
(720, 54, 'Ferizli', 'ferizli'),
(721, 54, 'Geyve', 'geyve'),
(722, 54, 'Karapürçek', 'karapurcek'),
(723, 54, 'Karasu', 'karasu'),
(724, 54, 'Kaynarca', 'kaynarca'),
(725, 54, 'Kocaali', 'kocaali'),
(726, 54, 'Pamukova', 'pamukova'),
(727, 54, 'Sapanca', 'sapanca'),
(728, 54, 'Söğütlü', 'sogutlu'),
(729, 54, 'Taraklı', 'tarakli'),
(730, 55, 'Atakum', 'atakum'),
(731, 55, 'İlkadım', 'ilkadim'),
(732, 55, 'Canik', 'canik'),
(733, 55, 'Tekkeköy', 'tekkekoy'),
(734, 55, 'Alaçam', 'alacam'),
(735, 55, 'Asarcık', 'asarcik'),
(736, 55, 'Ayvacık', 'ayvacik'),
(737, 55, 'Bafra', 'bafra'),
(738, 55, 'Çarşamba', 'carsamba'),
(739, 55, 'Havza', 'havza'),
(740, 55, 'Kavak', 'kavak'),
(741, 55, 'Ladik', 'ladik'),
(742, 55, '19Mayıs(Ballıca)', '19mayisballica'),
(743, 55, 'Salıpazarı', 'salipazari'),
(744, 55, 'Terme', 'terme'),
(745, 55, 'Vezirköprü', 'vezirkopru'),
(746, 55, 'Yakakent', 'yakakent'),
(747, 56, 'merkez', 'siirt'),
(748, 56, 'Baykan', 'baykan'),
(749, 56, 'Eruh', 'eruh'),
(750, 56, 'Kurtalan', 'kurtalan'),
(751, 56, 'Pervari', 'pervari'),
(752, 56, 'Aydınlar', 'aydinlar'),
(753, 56, 'Şirvan', 'sirvan'),
(754, 57, 'merkez', 'sinop'),
(755, 57, 'Ayancık', 'ayancik'),
(756, 57, 'Boyabat', 'boyabat'),
(757, 57, 'Dikmen', 'dikmen'),
(758, 57, 'Durağan', 'duragan'),
(759, 57, 'Erfelek', 'erfelek'),
(760, 57, 'Gerze', 'gerze'),
(761, 57, 'Saraydüzü', 'sarayduzu'),
(762, 57, 'Türkeli', 'turkeli'),
(763, 58, 'merkez', 'sivas'),
(764, 58, 'Akıncılar', 'akincilar'),
(765, 58, 'Altınyayla', 'altinyayla'),
(766, 58, 'Divriği', 'divrigi'),
(767, 58, 'Doğanşar', 'dogansar'),
(768, 58, 'Gemerek', 'gemerek'),
(769, 58, 'Gölova', 'golova'),
(770, 58, 'Gürün', 'gurun'),
(771, 58, 'Hafik', 'hafik'),
(772, 58, 'İmranlı', 'imranli'),
(773, 58, 'Kangal', 'kangal'),
(774, 58, 'Koyulhisar', 'koyulhisar'),
(775, 58, 'Suşehri', 'susehri'),
(776, 58, 'Şarkışla', 'sarkisla'),
(777, 58, 'Ulaş', 'ulas'),
(778, 58, 'Yıldızeli', 'yildizeli'),
(779, 58, 'Zara', 'zara'),
(780, 59, 'merkez', 'tekirdag'),
(781, 59, 'Çerkezköy', 'cerkezkoy'),
(782, 59, 'Çorlu', 'corlu'),
(783, 59, 'Hayrabolu', 'hayrabolu'),
(784, 59, 'Malkara', 'malkara'),
(785, 59, 'Marmaraereğlisi', 'marmaraereglisi'),
(786, 59, 'Muratlı', 'muratli'),
(787, 59, 'Saray', 'saray'),
(788, 59, 'Şarköy', 'sarkoy'),
(789, 60, 'merkez', 'tokat'),
(790, 60, 'Almus', 'almus'),
(791, 60, 'Artova', 'artova'),
(792, 60, 'Başçiftlik', 'basciftlik'),
(793, 60, 'Erbaa', 'erbaa'),
(794, 60, 'Niksar', 'niksar'),
(795, 60, 'Pazar', 'pazar'),
(796, 60, 'Reşadiye', 'resadiye'),
(797, 60, 'Sulusaray', 'sulusaray'),
(798, 60, 'Turhal', 'turhal'),
(799, 60, 'Yeşilyurt', 'yesilyurt'),
(800, 60, 'Zile', 'zile'),
(801, 61, 'merkez', 'trabzon'),
(802, 61, 'Akçaabat', 'akcaabat'),
(803, 61, 'Araklı', 'arakli'),
(804, 61, 'Arsin', 'arsin'),
(805, 61, 'Beşikdüzü', 'besikduzu'),
(806, 61, 'Çarşıbaşı', 'carsibasi'),
(807, 61, 'Çaykara', 'caykara'),
(808, 61, 'Dernekpazarı', 'dernekpazari'),
(809, 61, 'Düzköy', 'duzkoy'),
(810, 61, 'Hayrat', 'hayrat'),
(811, 61, 'Köprübaşı', 'koprubasi'),
(812, 61, 'Maçka', 'macka'),
(813, 61, 'Of', 'of'),
(814, 61, 'Sürmene', 'surmene'),
(815, 61, 'Şalpazarı', 'salpazari'),
(816, 61, 'Tonya', 'tonya'),
(817, 61, 'Vakfıkebir', 'vakfikebir'),
(818, 61, 'Yomra', 'yomra'),
(819, 62, 'merkez', 'tunceli'),
(820, 62, 'Çemişgezek', 'cemisgezek'),
(821, 62, 'Hozat', 'hozat'),
(822, 62, 'Mazgirt', 'mazgirt'),
(823, 62, 'Nazımiye', 'nazimiye'),
(824, 62, 'Ovacık', 'ovacik'),
(825, 62, 'Pertek', 'pertek'),
(826, 62, 'Pülümür', 'pulumur'),
(827, 63, 'merkez', 'sanliurfa'),
(828, 63, 'Akçakale', 'akcakale'),
(829, 63, 'Birecik', 'birecik'),
(830, 63, 'Bozova', 'bozova'),
(831, 63, 'Ceylanpınar', 'ceylanpinar'),
(832, 63, 'Halfeti', 'halfeti'),
(833, 63, 'Harran', 'harran'),
(834, 63, 'Hilvan', 'hilvan'),
(835, 63, 'Siverek', 'siverek'),
(836, 63, 'Suruç', 'suruc'),
(837, 63, 'Viranşehir', 'viransehir'),
(838, 64, 'merkez', 'usak'),
(839, 64, 'Banaz', 'banaz'),
(840, 64, 'Eşme', 'esme'),
(841, 64, 'Karahallı', 'karahalli'),
(842, 64, 'Sivaslı', 'sivasli'),
(843, 64, 'Ulubey', 'ulubey'),
(844, 65, 'merkez', 'van'),
(845, 65, 'Bahçesaray', 'bahcesaray'),
(846, 65, 'Başkale', 'baskale'),
(847, 65, 'Çaldıran', 'caldiran'),
(848, 65, 'Çatak', 'catak'),
(849, 65, 'Edremit(Gümüşdere)', 'edremitgumusdere'),
(850, 65, 'Erciş', 'ercis'),
(851, 65, 'Gevaş', 'gevas'),
(852, 65, 'Gürpınar', 'gurpinar'),
(853, 65, 'Muradiye', 'muradiye'),
(854, 65, 'Özalp', 'ozalp'),
(855, 65, 'Saray', 'saray'),
(856, 66, 'merkez', 'yozgat'),
(857, 66, 'Akdağmadeni', 'akdagmadeni'),
(858, 66, 'Aydıncık', 'aydincik'),
(859, 66, 'Boğazlıyan', 'bogazliyan'),
(860, 66, 'Çandır', 'candir'),
(861, 66, 'Çayıralan', 'cayiralan'),
(862, 66, 'Çekerek', 'cekerek'),
(863, 66, 'Kadışehri', 'kadisehri'),
(864, 66, 'Saraykent', 'saraykent'),
(865, 66, 'Sarıkaya', 'sarikaya'),
(866, 66, 'Sorgun', 'sorgun'),
(867, 66, 'Şefaatli', 'sefaatli'),
(868, 66, 'Yenifakılı', 'yenifakili'),
(869, 66, 'Yerköy', 'yerkoy'),
(870, 67, 'merkez', 'zonguldak'),
(871, 67, 'Alaplı', 'alapli'),
(872, 67, 'Çaycuma', 'caycuma'),
(873, 67, 'Devrek', 'devrek'),
(874, 67, 'Karadenizereğli', 'karadenizeregli'),
(875, 67, 'Gökçebey', 'gokcebey'),
(876, 68, 'merkez', 'aksaray'),
(877, 68, 'Ağaçören', 'agacoren'),
(878, 68, 'Eskil', 'eskil'),
(879, 68, 'Gülağaç(Ağaçlı)', 'gulagacagacli'),
(880, 68, 'Güzelyurt', 'guzelyurt'),
(881, 68, 'Ortaköy', 'ortakoy'),
(882, 68, 'Sarıyahşi', 'sariyahsi'),
(883, 69, 'merkez', 'bayburt'),
(884, 69, 'Aydıntepe', 'aydintepe'),
(885, 69, 'Demirözü', 'demirozu'),
(886, 70, 'merkez', 'karaman'),
(887, 70, 'Ayrancı', 'ayranci'),
(888, 70, 'Başyayla', 'basyayla'),
(889, 70, 'Ermenek', 'ermenek'),
(890, 70, 'Kazımkarabekir', 'kazimkarabekir'),
(891, 70, 'Sarıveliler', 'sariveliler'),
(892, 71, 'merkez', 'kirikkale'),
(893, 71, 'Bahşili', 'bahsili'),
(894, 71, 'Balışeyh', 'baliseyh'),
(895, 71, 'Çelebi', 'celebi'),
(896, 71, 'Delice', 'delice'),
(897, 71, 'Karakeçili', 'karakecili'),
(898, 71, 'Keskin', 'keskin'),
(899, 71, 'Sulakyurt', 'sulakyurt'),
(900, 71, 'Yahşihan', 'yahsihan'),
(901, 72, 'merkez', 'batman'),
(902, 72, 'Beşiri', 'besiri'),
(903, 72, 'Gercüş', 'gercus'),
(904, 72, 'Hasankeyf', 'hasankeyf'),
(905, 72, 'Kozluk', 'kozluk'),
(906, 72, 'Sason', 'sason'),
(907, 73, 'merkez', 'sirnak'),
(908, 73, 'Beytüşşebap', 'beytussebap'),
(909, 73, 'Cizre', 'cizre'),
(910, 73, 'Güçlükonak', 'guclukonak'),
(911, 73, 'İdil', 'idil'),
(912, 73, 'Silopi', 'silopi'),
(913, 73, 'Uludere', 'uludere'),
(914, 74, 'merkez', 'bartin'),
(915, 74, 'Amasra', 'amasra'),
(916, 74, 'Kurucaşile', 'kurucasile'),
(917, 74, 'Ulus', 'ulus'),
(918, 75, 'merkez', 'ardahan'),
(919, 75, 'Çıldır', 'cildir'),
(920, 75, 'Damal', 'damal'),
(921, 75, 'Göle', 'gole'),
(922, 75, 'Hanak', 'hanak'),
(923, 75, 'Posof', 'posof'),
(924, 76, 'merkez', 'igdir'),
(925, 76, 'Aralık', 'aralik'),
(926, 76, 'Karakoyunlu', 'karakoyunlu'),
(927, 76, 'Tuzluca', 'tuzluca'),
(928, 77, 'merkez', 'yalova'),
(929, 77, 'Altınova', 'altinova'),
(930, 77, 'Armutlu', 'armutlu'),
(931, 77, 'Çiftlikköy', 'ciftlikkoy'),
(932, 77, 'Çınarcık', 'cinarcik'),
(933, 77, 'Termal', 'termal'),
(934, 78, 'merkez', 'karabuk'),
(935, 78, 'Eflani', 'eflani'),
(936, 78, 'Eskipazar', 'eskipazar'),
(937, 78, 'Ovacık', 'ovacik'),
(938, 78, 'Safranbolu', 'safranbolu'),
(939, 78, 'Yenice', 'yenice'),
(940, 79, 'merkez', 'kilis'),
(941, 79, 'Elbeyli', 'elbeyli'),
(942, 79, 'Musabeyli', 'musabeyli'),
(943, 79, 'Polateli', 'polateli'),
(944, 80, 'merkez', 'osmaniye'),
(945, 80, 'Bahçe', 'bahce'),
(946, 80, 'Düziçi', 'duzici'),
(947, 80, 'Hasanbeyli', 'hasanbeyli'),
(948, 80, 'Kadirli', 'kadirli'),
(949, 80, 'Sumbas', 'sumbas'),
(950, 80, 'Toprakkale', 'toprakkale'),
(951, 81, 'merkez', 'duzce'),
(952, 81, 'Akçakoca', 'akcakoca'),
(953, 81, 'Cumayeri', 'cumayeri'),
(954, 81, 'Çilimli', 'cilimli'),
(955, 81, 'Gölyaka', 'golyaka'),
(956, 81, 'Gümüşova', 'gumusova'),
(957, 81, 'Kaynaşlı', 'kaynasli'),
(958, 81, 'Yığılca', 'yigilca'),
(962, 20, 'Pamukkale', 'pamukkale'),
(963, 7, 'Olympos', 'olympos'),
(964, 7, 'Çıralı', 'cirali'),
(965, 7, 'Kaleiçi', 'kaleici'),
(967, 33, 'Kızkalesi', 'kizkalesi'),
(968, 20, 'Karahayit', 'karahayit');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `kurumsal`
--

DROP TABLE IF EXISTS `kurumsal`;
CREATE TABLE `kurumsal` (
  `id` int(11) UNSIGNED NOT NULL,
  `il_id` tinyint(2) UNSIGNED NOT NULL,
  `ilce_id` smallint(5) UNSIGNED NOT NULL,
  `ad` varchar(120) COLLATE utf8_turkish_ci NOT NULL,
  `kullaniciadi` varchar(40) COLLATE utf8_turkish_ci NOT NULL,
  `email` varchar(254) COLLATE utf8_turkish_ci NOT NULL,
  `sifre` varchar(254) COLLATE utf8_turkish_ci NOT NULL,
  `ceptel` char(16) COLLATE utf8_turkish_ci DEFAULT NULL,
  `adres` varchar(254) COLLATE utf8_turkish_ci NOT NULL,
  `minAlimTutar` tinyint(4) UNSIGNED DEFAULT 1,
  `acikMi` tinyint(1) DEFAULT 0,
  `kayit_tarihi` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `kurumsal`
--

INSERT INTO `kurumsal` (`id`, `il_id`, `ilce_id`, `ad`, `kullaniciadi`, `email`, `sifre`, `ceptel`, `adres`, `minAlimTutar`, `acikMi`, `kayit_tarihi`) VALUES
(1, 34, 422, 'Bambi cafe', 'cafebambi', 'bambi@cafebambi.com', '8a5da52ed126447d359e70c05721a8aa', '5413474141', 'Ayışığı Sk No:61', 15, 1, '2020-03-09 20:13:11'),
(2, 34, 422, 'Balat Cafe Express', 'cafebalat', 'balat@balatcafe.co', 'aa1c8371ebd158fb3966a146e5f9ed45', '5413474147', 'Alipaşa Sk No:2', 25, 1, '2020-03-10 10:28:30'),
(3, 34, 442, 'Yeşilim Restaurant', 'restyes', 'yesilim@rest.com', 'aa1c8371ebd158fb3966a146e5f9ed45', '5413474148', 'Batı Mh. Maltepe Sk. No:34', 40, 1, '2020-03-10 10:28:30'),
(4, 61, 802, 'Tadım Lokanta', 'tadimlok', 'tadim@tadimlokanta.net', 'aa1c8371ebd158fb3966a146e5f9ed45', '5413474144', 'İnönü Cd. No:61 ', 20, 1, '2020-03-10 22:18:41'),
(5, 61, 802, 'İhlas Köfte', 'ihlaskofte', 'ihlas@kofte.com', 'aa1c8371ebd158fb3966a146e5f9ed45', '5413474146', 'Orta Mh. 61.Sk No:61', 44, 0, '2020-03-10 22:18:41'),
(6, 63, 835, 'Siverek Cafe', 'cafesiverek', 'cafesiv@siverek.com', 'aa1c8371ebd158fb3966a146e5f9ed45', '5413474143', 'Mumışığı Sk No:1', 27, 0, '2020-03-10 22:20:42'),
(7, 63, 835, 'Dikkat Launch', 'dikkatlaunch', 'dikkat@dikkat.net', 'aa1c8371ebd158fb3966a146e5f9ed45', '5413474145', 'Hamamçimeni İş Merkezi No:23', 38, 0, '2020-03-10 22:20:42'),
(8, 81, 951, 'umutlu Tantuni', 'tantuniumutlu', 'umutlu@tantuni.com', 'aa1c8371ebd158fb3966a146e5f9ed45', NULL, 'orhangazi mah', 5, 0, '2020-03-11 19:50:25'),
(9, 81, 951, 'Beçi Lokanta', 'becilokanta', 'hesap@kurumsal.com', '8a5da52ed126447d359e70c05721a8aa', '5413474142', 'Soğuk Sk No:202/A', 6, 1, '2020-03-11 19:50:25'),
(53, 61, 802, 'Cafe de Albert', 'omurserdarr', 'omurserdarr@gmail.com', 'aa1c8371ebd158fb3966a146e5f9ed45', '(541) 347 - 4150', '', 1, 1, '2020-05-22 23:36:17');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `sepet`
--

DROP TABLE IF EXISTS `sepet`;
CREATE TABLE `sepet` (
  `id` int(11) NOT NULL,
  `bireysel_id` int(11) UNSIGNED NOT NULL,
  `kurumsal_id` int(11) NOT NULL,
  `envanter_id` int(11) NOT NULL,
  `adet` tinyint(2) UNSIGNED DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `sepet`
--

INSERT INTO `sepet` (`id`, `bireysel_id`, `kurumsal_id`, `envanter_id`, `adet`) VALUES
(132, 1, 2, 1728, 2),
(141, 2, 2, 1740, 1),
(143, 2, 2, 1728, 1);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `siparis`
--

DROP TABLE IF EXISTS `siparis`;
CREATE TABLE `siparis` (
  `siparisKod` varchar(14) CHARACTER SET utf8 COLLATE utf8_turkish_ci NOT NULL,
  `bireysel_id` int(11) UNSIGNED NOT NULL,
  `kurumsal_id` int(11) UNSIGNED NOT NULL,
  `durum_id` tinyint(1) UNSIGNED NOT NULL,
  `siparisTarih` datetime DEFAULT current_timestamp(),
  `toplamTutar` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_turkish_ci;

--
-- Tablo döküm verisi `siparis`
--

INSERT INTO `siparis` (`siparisKod`, `bireysel_id`, `kurumsal_id`, `durum_id`, `siparisTarih`, `toplamTutar`) VALUES
('167689666566', 2, 2, 1, '2020-07-16 12:51:09', 28),
('919228885607', 2, 1, 2, '2020-07-16 12:49:22', 33);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `siparisDetay`
--

DROP TABLE IF EXISTS `siparisDetay`;
CREATE TABLE `siparisDetay` (
  `siparisDetayId` int(11) NOT NULL,
  `siparisKod` varchar(14) COLLATE utf8_turkish_ci NOT NULL,
  `envanter_id` int(11) NOT NULL,
  `adet` tinyint(2) UNSIGNED NOT NULL,
  `tutar` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci ROW_FORMAT=COMPACT;

--
-- Tablo döküm verisi `siparisDetay`
--

INSERT INTO `siparisDetay` (`siparisDetayId`, `siparisKod`, `envanter_id`, `adet`, `tutar`) VALUES
(109, '919228885607', 1581, 1, 11),
(110, '919228885607', 1567, 1, 8),
(111, '919228885607', 1611, 1, 14),
(112, '167689666566', 1743, 1, 6),
(113, '167689666566', 1728, 1, 22);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `siparisDurum`
--

DROP TABLE IF EXISTS `siparisDurum`;
CREATE TABLE `siparisDurum` (
  `id` tinyint(1) UNSIGNED NOT NULL,
  `tanim` tinytext COLLATE utf8_turkish_ci NOT NULL DEFAULT 'Yeni Sipariş'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `siparisDurum`
--

INSERT INTO `siparisDurum` (`id`, `tanim`) VALUES
(1, 'Yeni Sipariş'),
(2, 'Sipariş Hazırlanıyor'),
(3, 'Gönderimde'),
(4, 'Tamamlandı');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `tabMenu`
--

DROP TABLE IF EXISTS `tabMenu`;
CREATE TABLE `tabMenu` (
  `id` int(11) NOT NULL,
  `kurumsal_id` int(11) UNSIGNED NOT NULL,
  `ad` varchar(50) COLLATE utf8_turkish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `tabMenu`
--

INSERT INTO `tabMenu` (`id`, `kurumsal_id`, `ad`) VALUES
(1, 1, 'Menüler'),
(2, 1, 'Kahvaltılıklar'),
(3, 1, 'Çorbalar'),
(4, 1, 'Tostlar'),
(5, 1, 'Ayvalık Tostları'),
(6, 1, 'Ekmek Arası Tostlar'),
(7, 1, 'Et Dönerler'),
(8, 1, 'Tavuk Dönerler'),
(9, 1, 'Burgerler'),
(10, 1, 'Sıcak Sandviçler'),
(11, 1, 'Patsolar'),
(12, 1, 'Soğuk Sandviçler'),
(13, 1, 'Kumpirler'),
(14, 1, 'Yan Ürünler'),
(15, 1, 'Tatlılar'),
(16, 1, 'İçecekler'),
(17, 1, 'Vitamin Bar'),
(22, 2, 'Çiğ Köfteler'),
(23, 2, 'Mezeler'),
(24, 2, 'Tatlılar'),
(25, 2, 'Yan Ürünler'),
(26, 2, 'İçecekler'),
(29, 3, 'Tostlar'),
(30, 3, 'Izgaralar'),
(31, 3, 'Ekmek Arası Ürünler'),
(32, 3, 'Diğer Lezzetler'),
(33, 3, 'İçecekler'),
(34, 3, 'Poşet'),
(35, 4, 'Menüler'),
(36, 4, 'Çorbalar'),
(37, 4, 'Tavuk Dönerler'),
(38, 4, 'Ekmek Arası Ürünler'),
(39, 4, 'Ev Yemekleri'),
(40, 4, 'Yan Ürünler'),
(41, 4, 'Tatlılar'),
(42, 4, 'İçecekler'),
(43, 4, 'Poşet'),
(44, 5, 'Menüler'),
(45, 5, 'Kahvaltılıklar'),
(46, 5, 'Fırın Ürünleri'),
(47, 5, 'Ekmek Arası Ürünler'),
(48, 5, 'İçecekler'),
(49, 5, 'Poşet'),
(50, 6, 'Kebaplar'),
(51, 6, 'Ekmek Arası Ürünler'),
(52, 6, 'Yan Ürünler'),
(53, 6, 'Tatlılar'),
(54, 6, 'İçecekler'),
(55, 7, 'Lahmacunlar'),
(56, 7, 'Pideler'),
(57, 7, 'İçecekler'),
(58, 7, 'Poşet'),
(59, 8, 'Lahmacunlar'),
(60, 8, 'Fırın Ürünleri'),
(61, 8, 'İçecekler'),
(62, 8, 'Poşet'),
(63, 9, 'Menüler'),
(64, 9, 'Çorbalar'),
(65, 9, 'Ana Yemekler'),
(66, 9, 'Ara Öğünler'),
(67, 9, 'Salatalar'),
(68, 9, 'İçecekler'),
(69, 9, 'Detoks İçecekler (40 cl.)'),
(117, 3, 'Abdurrezzak'),
(144, 53, 'Poşetler'),
(147, 9, 'Örnek');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `bireysel`
--
ALTER TABLE `bireysel`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `kullaniciadi` (`kullaniciadi`),
  ADD KEY `il_id` (`il_id`),
  ADD KEY `ilce_id` (`ilce_id`);

--
-- Tablo için indeksler `degerlendirme`
--
ALTER TABLE `degerlendirme`
  ADD PRIMARY KEY (`id`),
  ADD KEY `siparisKod` (`siparisKod`);

--
-- Tablo için indeksler `envanter`
--
ALTER TABLE `envanter`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tabMenu_id` (`tabMenu_id`);

--
-- Tablo için indeksler `il`
--
ALTER TABLE `il`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `ilce`
--
ALTER TABLE `ilce`
  ADD PRIMARY KEY (`id`),
  ADD KEY `il_id` (`il_id`);

--
-- Tablo için indeksler `kurumsal`
--
ALTER TABLE `kurumsal`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `kullaniciadi` (`kullaniciadi`),
  ADD KEY `il_id` (`il_id`),
  ADD KEY `ilce_id` (`ilce_id`);

--
-- Tablo için indeksler `sepet`
--
ALTER TABLE `sepet`
  ADD PRIMARY KEY (`id`),
  ADD KEY `envanter_id` (`envanter_id`),
  ADD KEY `bireysel_id` (`bireysel_id`);

--
-- Tablo için indeksler `siparis`
--
ALTER TABLE `siparis`
  ADD PRIMARY KEY (`siparisKod`),
  ADD KEY `bireysel_id` (`bireysel_id`),
  ADD KEY `kurumsal_id` (`kurumsal_id`),
  ADD KEY `durum_id` (`durum_id`);

--
-- Tablo için indeksler `siparisDetay`
--
ALTER TABLE `siparisDetay`
  ADD PRIMARY KEY (`siparisDetayId`),
  ADD KEY `siparisKod` (`siparisKod`);

--
-- Tablo için indeksler `siparisDurum`
--
ALTER TABLE `siparisDurum`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `tabMenu`
--
ALTER TABLE `tabMenu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kurumsal_id` (`kurumsal_id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `bireysel`
--
ALTER TABLE `bireysel`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- Tablo için AUTO_INCREMENT değeri `degerlendirme`
--
ALTER TABLE `degerlendirme`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Tablo için AUTO_INCREMENT değeri `envanter`
--
ALTER TABLE `envanter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2243;

--
-- Tablo için AUTO_INCREMENT değeri `kurumsal`
--
ALTER TABLE `kurumsal`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- Tablo için AUTO_INCREMENT değeri `sepet`
--
ALTER TABLE `sepet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=144;

--
-- Tablo için AUTO_INCREMENT değeri `siparisDetay`
--
ALTER TABLE `siparisDetay`
  MODIFY `siparisDetayId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=114;

--
-- Tablo için AUTO_INCREMENT değeri `siparisDurum`
--
ALTER TABLE `siparisDurum`
  MODIFY `id` tinyint(1) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Tablo için AUTO_INCREMENT değeri `tabMenu`
--
ALTER TABLE `tabMenu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=148;

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `bireysel`
--
ALTER TABLE `bireysel`
  ADD CONSTRAINT `bireysel_ibfk_1` FOREIGN KEY (`il_id`) REFERENCES `il` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `bireysel_ibfk_2` FOREIGN KEY (`ilce_id`) REFERENCES `ilce` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Tablo kısıtlamaları `degerlendirme`
--
ALTER TABLE `degerlendirme`
  ADD CONSTRAINT `degerlendirme_ibfk_1` FOREIGN KEY (`siparisKod`) REFERENCES `siparis` (`siparisKod`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Tablo kısıtlamaları `envanter`
--
ALTER TABLE `envanter`
  ADD CONSTRAINT `envanter_ibfk_1` FOREIGN KEY (`tabMenu_id`) REFERENCES `tabMenu` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Tablo kısıtlamaları `ilce`
--
ALTER TABLE `ilce`
  ADD CONSTRAINT `ilce_ibfk_1` FOREIGN KEY (`il_id`) REFERENCES `il` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Tablo kısıtlamaları `kurumsal`
--
ALTER TABLE `kurumsal`
  ADD CONSTRAINT `kurumsal_ibfk_1` FOREIGN KEY (`il_id`) REFERENCES `il` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `kurumsal_ibfk_2` FOREIGN KEY (`ilce_id`) REFERENCES `ilce` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Tablo kısıtlamaları `sepet`
--
ALTER TABLE `sepet`
  ADD CONSTRAINT `sepet_ibfk_1` FOREIGN KEY (`envanter_id`) REFERENCES `envanter` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sepet_ibfk_2` FOREIGN KEY (`bireysel_id`) REFERENCES `bireysel` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Tablo kısıtlamaları `siparis`
--
ALTER TABLE `siparis`
  ADD CONSTRAINT `siparis_ibfk_1` FOREIGN KEY (`bireysel_id`) REFERENCES `bireysel` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `siparis_ibfk_2` FOREIGN KEY (`kurumsal_id`) REFERENCES `kurumsal` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `siparis_ibfk_3` FOREIGN KEY (`durum_id`) REFERENCES `siparisDurum` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Tablo kısıtlamaları `siparisDetay`
--
ALTER TABLE `siparisDetay`
  ADD CONSTRAINT `siparisDetay_ibfk_1` FOREIGN KEY (`siparisKod`) REFERENCES `siparis` (`siparisKod`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Tablo kısıtlamaları `tabMenu`
--
ALTER TABLE `tabMenu`
  ADD CONSTRAINT `tabMenu_ibfk_1` FOREIGN KEY (`kurumsal_id`) REFERENCES `kurumsal` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
