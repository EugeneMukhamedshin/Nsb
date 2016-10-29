--
-- Скрипт сгенерирован Devart dbForge Studio for MySQL, Версия 7.1.13.0
-- Домашняя страница продукта: http://www.devart.com/ru/dbforge/mysql/studio
-- Дата скрипта: 27.10.2016 22:17:27
-- Версия сервера: 5.7.13-log
-- Версия клиента: 4.1
--


-- 
-- Отключение внешних ключей
-- 
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;

-- 
-- Установить режим SQL (SQL mode)
-- 
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- 
-- Установка кодировки, с использованием которой клиент будет посылать запросы на сервер
--
SET NAMES 'utf8';

-- 
-- Установка базы данных по умолчанию
--
USE nsb;

--
-- Описание для таблицы model_files
--
DROP TABLE IF EXISTS model_files;
CREATE TABLE model_files (
  id INT(11) NOT NULL AUTO_INCREMENT,
  model_id INT(11) NOT NULL,
  file_name VARCHAR(50) NOT NULL,
  translit_file_name VARCHAR(50) NOT NULL,
  file_type INT(11) NOT NULL,
  is_valid INT(11) NOT NULL DEFAULT 0,
  is_load INT(11) DEFAULT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB
AUTO_INCREMENT = 285
AVG_ROW_LENGTH = 133
CHARACTER SET utf8
COLLATE utf8_general_ci
ROW_FORMAT = DYNAMIC;

--
-- Описание для таблицы models
--
DROP TABLE IF EXISTS models;
CREATE TABLE models (
  id INT(11) NOT NULL AUTO_INCREMENT,
  name VARCHAR(50) NOT NULL,
  description VARCHAR(1000) DEFAULT NULL,
  link VARCHAR(255) DEFAULT NULL,
  add_ground INT(11) NOT NULL DEFAULT 1,
  enable_shadows INT(11) NOT NULL DEFAULT 1,
  is_valid INT(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (id)
)
ENGINE = INNODB
AUTO_INCREMENT = 132
AVG_ROW_LENGTH = 1820
CHARACTER SET utf8
COLLATE utf8_general_ci
COMMENT = 'Модели лабиринтов'
ROW_FORMAT = DYNAMIC;

--
-- Описание для пользователя `mysql.sys`
--
DROP USER IF EXISTS 'mysql.sys'@'localhost';
CREATE USER 'mysql.sys'@'localhost' IDENTIFIED WITH mysql_native_password PASSWORD EXPIRE DEFAULT ACCOUNT LOCK;
GRANT ALL PRIVILEGES ON *.* TO 'mysql.sys'@'localhost' 
WITH GRANT OPTION;

--
-- Описание для пользователя root
--
DROP USER IF EXISTS 'root'@'localhost';
CREATE USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password AS '*81F5E21E35407D884A6CD4A731AEBFB6AF209E1B' PASSWORD EXPIRE DEFAULT;
GRANT ALL PRIVILEGES ON *.* TO 'root'@'localhost' 
WITH GRANT OPTION;

-- 
-- Вывод данных для таблицы model_files
--
INSERT INTO model_files VALUES
(160, 2, 'zl-24_new_mtl.mtl', 'zl-24_new_mtl.mtl', 2, 0, NULL),
(161, 2, 'веревка зел.jpg', 'verevka zel.jpg', 3, 0, NULL),
(162, 2, 'веревка_bump.jpg', 'verevka_bump.jpg', 3, 0, NULL),
(163, 2, 'ин.jpg', 'in.jpg', 3, 0, NULL),
(164, 2, 'птица прозр.png', 'ptitsa prozr.png', 3, 0, NULL),
(165, 2, 'птица.png', 'ptitsa.png', 3, 0, NULL),
(166, 2, 'стрекозный витраж.jpg', 'strekoznyy vitrazh.jpg', 3, 0, NULL),
(167, 2, 'цветовой круг иам.jpg', 'tsvetovoy krug iam.jpg', 3, 0, NULL),
(168, 2, 'zl-24_new_mtl.obj', 'zl-24_new_mtl.obj', 1, 0, NULL),
(169, 103, 'castIronTexture_00011.jpg', 'castIronTexture_00011.jpg', 3, 0, NULL),
(170, 103, 'ol-7.mtl', 'ol-7.mtl', 2, 0, NULL),
(171, 103, 'веревка зел1.jpg', 'verevka zel1.jpg', 3, 0, NULL),
(172, 103, 'круговая1.jpg', 'krugovaya1.jpg', 3, 0, NULL),
(173, 103, 'круговаяопасити.jpg', 'krugovayaopasiti.jpg', 3, 0, NULL),
(174, 103, 'прозрачный.jpg', 'prozrachnyy.jpg', 3, 0, NULL),
(175, 103, 'ol-7.obj', 'ol-7.obj', 1, 0, NULL),
(176, 104, 'grass_noise.jpg', 'grass_noise.jpg', 3, 0, NULL),
(177, 104, 'ol-01.mtl', 'ol-01.mtl', 2, 0, NULL),
(178, 104, 'бамп 1.jpg', 'bamp 1.jpg', 3, 0, NULL),
(179, 104, 'прозрачный.jpg', 'prozrachnyy.jpg', 3, 0, NULL),
(180, 104, 'светлый2.jpg', 'svetlyy2.jpg', 3, 0, NULL),
(181, 104, 'темнее.jpg', 'temnee.jpg', 3, 0, NULL),
(182, 104, 'тросс бамп.jpg', 'tross bamp.jpg', 3, 0, NULL),
(183, 104, 'тросс зеленый.jpg', 'tross zelenyy.jpg', 3, 0, NULL),
(184, 104, 'тросс.jpg', 'tross.jpg', 3, 0, NULL),
(185, 104, 'ol-01.obj', 'ol-01.obj', 1, 0, NULL),
(186, 105, 'mif-67.mtl', 'mif-67.mtl', 2, 0, NULL),
(187, 105, 'mif-67.obj', 'mif-67.obj', 1, 0, NULL),
(188, 106, 'as2_wood_02b.jpg', 'as2_wood_02b.jpg', 3, 0, NULL),
(189, 106, 'IL-14.mtl', 'IL-14.mtl', 2, 0, NULL),
(190, 106, 'веревка зел.jpg', 'verevka zel.jpg', 3, 0, NULL),
(191, 106, 'Копия AS2_wood_02.jpg', 'Kopiya AS2_wood_02.jpg', 3, 0, NULL),
(192, 106, 'светлый2.jpg', 'svetlyy2.jpg', 3, 0, NULL),
(193, 106, 'термо-сосна_колор- светлый махагон.jpg', 'termo-sosna_kolor- svetlyy makhagon.jpg', 3, 0, NULL),
(194, 106, 'IL-14.obj', 'IL-14.obj', 1, 0, NULL),
(195, 107, 'zl-32_other.mtl', 'zl-32_other.mtl', 2, 0, NULL),
(196, 107, 'веревка зел.jpg', 'verevka zel.jpg', 3, 0, NULL),
(197, 107, 'лягушонок панелька.jpg', 'lyagushonok panelka.jpg', 3, 0, NULL),
(198, 107, 'светлый2.jpg', 'svetlyy2.jpg', 3, 0, NULL),
(199, 107, 'стрекозка 494 х 227.jpg', 'strekozka 494 kh 227.jpg', 3, 0, NULL),
(200, 107, 'zl-32_other.obj', 'zl-32_other.obj', 1, 0, NULL),
(201, 108, 'zl-32_original.mtl', 'zl-32_original.mtl', 2, 0, NULL),
(202, 108, 'веревка зел.jpg', 'verevka zel.jpg', 3, 0, NULL),
(203, 108, 'лягушонок панелька.jpg', 'lyagushonok panelka.jpg', 3, 0, NULL),
(204, 108, 'светлый2.jpg', 'svetlyy2.jpg', 3, 0, NULL),
(205, 108, 'стрекозка 494 х 227.jpg', 'strekozka 494 kh 227.jpg', 3, 0, NULL),
(206, 108, 'zl-32_original.obj', 'zl-32_original.obj', 1, 0, NULL),
(207, 109, 'zl-13.mtl', 'zl-13.mtl', 2, 0, NULL),
(208, 109, 'веревка зел.jpg', 'verevka zel.jpg', 3, 0, NULL),
(209, 109, 'веревка_bump.jpg', 'verevka_bump.jpg', 3, 0, NULL),
(210, 109, 'термо-сосна_колор- светлый махагон.jpg', 'termo-sosna_kolor- svetlyy makhagon.jpg', 3, 0, NULL),
(211, 109, 'zl-13.obj', 'zl-13.obj', 1, 0, NULL),
(212, 110, 'zl-12_original.mtl', 'zl-12_original.mtl', 2, 0, NULL),
(213, 110, 'веревка зел.jpg', 'verevka zel.jpg', 3, 0, NULL),
(214, 110, 'светлый2.jpg', 'svetlyy2.jpg', 3, 0, NULL),
(215, 110, 'zl-12_original.obj', 'zl-12_original.obj', 1, 0, NULL),
(216, 111, 'zl-24_standart_mtl.mtl', 'zl-24_standart_mtl.mtl', 2, 0, NULL),
(217, 111, 'веревка зел.jpg', 'verevka zel.jpg', 3, 0, NULL),
(218, 111, 'веревка_bump.jpg', 'verevka_bump.jpg', 3, 0, NULL),
(219, 111, 'ин.jpg', 'in.jpg', 3, 0, NULL),
(220, 111, 'ин2.jpg', 'in2.jpg', 3, 0, NULL),
(221, 111, 'птица прозр.png', 'ptitsa prozr.png', 3, 0, NULL),
(222, 111, 'птица.png', 'ptitsa.png', 3, 0, NULL),
(223, 111, 'стрекозный витраж.jpg', 'strekoznyy vitrazh.jpg', 3, 0, NULL),
(224, 111, 'цветовой круг иам.jpg', 'tsvetovoy krug iam.jpg', 3, 0, NULL),
(225, 111, 'zl-24_standart_mtl.obj', 'zl-24_standart_mtl.obj', 1, 0, NULL),
(226, 112, 'ZL-24.mtl', 'ZL-24.mtl', 2, 0, NULL),
(227, 112, 'ин.jpg', 'in.jpg', 3, 0, NULL),
(228, 112, 'опасити стрекозы.jpg', 'opasiti strekozy.jpg', 3, 0, NULL),
(229, 112, 'птица прозр.png', 'ptitsa prozr.png', 3, 0, NULL),
(230, 112, 'птица.png', 'ptitsa.png', 3, 0, NULL),
(231, 112, 'стрекозный витраж опасити.ai', 'strekoznyy vitrazh opasiti.ai', 3, 0, NULL),
(232, 112, 'стрекозный витраж опасити.jpg', 'strekoznyy vitrazh opasiti.jpg', 3, 0, NULL),
(233, 112, 'стрекозный витраж опасити1.jpg', 'strekoznyy vitrazh opasiti1.jpg', 3, 0, NULL),
(234, 112, 'стрекозный витраж опасити11.jpg', 'strekoznyy vitrazh opasiti11.jpg', 3, 0, NULL),
(235, 112, 'стрекозный витраж опасити12.jpg', 'strekoznyy vitrazh opasiti12.jpg', 3, 0, NULL),
(236, 112, 'стрекозный витраж опасити13.jpg', 'strekoznyy vitrazh opasiti13.jpg', 3, 0, NULL),
(237, 112, 'стрекозный витраж опасити15.jpg', 'strekoznyy vitrazh opasiti15.jpg', 3, 0, NULL),
(238, 112, 'стрекозный витраж опасити16.jpg', 'strekoznyy vitrazh opasiti16.jpg', 3, 0, NULL),
(239, 112, 'стрекозный витраж.ai', 'strekoznyy vitrazh.ai', 3, 0, NULL),
(240, 112, 'стрекозный витраж.jpg', 'strekoznyy vitrazh.jpg', 3, 0, NULL),
(241, 112, 'стрекозный витраж1.ai', 'strekoznyy vitrazh1.ai', 3, 0, NULL),
(242, 112, 'ZL-24.obj', 'ZL-24.obj', 1, 0, NULL),
(243, 126, 'grass_noise.jpg', 'grass_noise.jpg', 3, 0, NULL),
(244, 126, 'ol-01.mtl', 'ol-01.mtl', 2, 0, NULL),
(245, 126, 'бамп 1.jpg', 'bamp 1.jpg', 3, 0, NULL),
(246, 126, 'прозрачный.jpg', 'prozrachnyy.jpg', 3, 0, NULL),
(247, 126, 'светлый2.jpg', 'svetlyy2.jpg', 3, 0, NULL),
(248, 126, 'темнее.jpg', 'temnee.jpg', 3, 0, NULL),
(249, 126, 'тросс бамп.jpg', 'tross bamp.jpg', 3, 0, NULL),
(250, 126, 'тросс зеленый.jpg', 'tross zelenyy.jpg', 3, 0, NULL),
(251, 126, 'тросс.jpg', 'tross.jpg', 3, 0, NULL),
(252, 126, 'ol-01.obj', 'ol-01.obj', 1, 0, NULL),
(253, 127, 'grass_noise.jpg', 'grass_noise.jpg', 3, 0, NULL),
(254, 127, 'ol-01.mtl', 'ol-01.mtl', 2, 0, NULL),
(255, 127, 'бамп 1.jpg', 'bamp 1.jpg', 3, 0, NULL),
(256, 127, 'прозрачный.jpg', 'prozrachnyy.jpg', 3, 0, NULL),
(257, 127, 'светлый2.jpg', 'svetlyy2.jpg', 3, 0, NULL),
(258, 127, 'темнее.jpg', 'temnee.jpg', 3, 0, NULL),
(259, 127, 'тросс бамп.jpg', 'tross bamp.jpg', 3, 0, NULL),
(260, 127, 'тросс зеленый.jpg', 'tross zelenyy.jpg', 3, 0, NULL),
(261, 127, 'тросс.jpg', 'tross.jpg', 3, 0, NULL),
(262, 127, 'ol-01.obj', 'ol-01.obj', 1, 0, NULL),
(263, 128, 'castIronTexture_00011.jpg', 'castIronTexture_00011.jpg', 3, 0, NULL),
(264, 128, 'ol-7.mtl', 'ol-7.mtl', 2, 0, NULL),
(265, 128, 'веревка зел1.jpg', 'verevka zel1.jpg', 3, 0, NULL),
(266, 128, 'круговая1.jpg', 'krugovaya1.jpg', 3, 0, NULL),
(267, 128, 'круговаяопасити.jpg', 'krugovayaopasiti.jpg', 3, 0, NULL),
(268, 128, 'прозрачный.jpg', 'prozrachnyy.jpg', 3, 0, NULL),
(269, 128, 'ol-7.obj', 'ol-7.obj', 1, 0, NULL),
(270, 129, 'zl-24.mtl', 'zl-24.mtl', 2, 0, NULL),
(271, 129, 'веревка зел.jpg', 'verevka zel.jpg', 3, 0, NULL),
(272, 129, 'птица.png', 'ptitsa.png', 3, 0, NULL),
(273, 129, 'стрекозный витраж.jpg', 'strekoznyy vitrazh.jpg', 3, 0, NULL),
(274, 129, 'цветовой круг иам.jpg', 'tsvetovoy krug iam.jpg', 3, 0, NULL),
(275, 129, 'zl-24.obj', 'zl-24.obj', 1, 0, NULL),
(276, 130, 'zl145.mtl', 'zl145.mtl', 2, 0, NULL),
(277, 130, 'zl145.obj', 'zl145.obj', 1, 0, NULL),
(278, 131, 'kps.mtl', 'kps.mtl', 2, 0, NULL),
(279, 131, 'mineral_gold_8.jpg', 'mineral_gold_8.jpg', 3, 0, NULL),
(280, 131, 'mineral_gold_22.jpg', 'mineral_gold_22.jpg', 3, 0, NULL),
(281, 131, 'kps.obj', 'kps.obj', 1, 0, NULL),
(282, 131, 'Копия AS2_wood_02.jpg', 'Kopiya AS2_wood_02.jpg', 3, 0, NULL),
(283, 131, 'термо-сосна Колор-натур.jpg', 'termo-sosna Kolor-natur.jpg', 3, 0, NULL),
(284, 131, 'фанера посветлее.jpg', 'fanera posvetlee.jpg', 3, 0, NULL);

-- 
-- Вывод данных для таблицы models
--
INSERT INTO models VALUES
(105, 'Игровой комплекс Миф-67', 'Серия "Заколдованный лес"', '', 1, 1, 0),
(106, 'Игровой комплекс ИЛ-14', 'Серия "Заколдованный лес"', '', 1, 1, 0),
(107, 'Игровой комплекс ЗЛ-32', 'Серия "Заколдованный лес"', '', 1, 1, 0),
(108, 'Игровой комплекс ЗЛ-32-1', 'Серия "Заколдованный лес"', '', 1, 1, 0),
(109, 'Игровой комплекс ЗЛ-13', 'Серия "Заколдованный лес"', '', 1, 1, 0),
(110, 'Игровой комплекс ЗЛ-12', 'Серия "Заколдованный лес"', '', 1, 1, 0),
(111, 'Игровой комплекс ЗЛ-24', 'Серия "Заколдованный лес"', '', 1, 1, 0),
(112, 'Игровой комплекс ЗЛ-24-1', 'Серия "Заколдованный лес"', '', 0, 1, 0),
(127, 'Игровой комплекс ОЛ-1', 'Серия «Оранжевое лето»', 'http://fun-terra.ru/catalog/seriya-oranzhevoe-leto/ol-01.html', 1, 1, 0),
(128, 'Игровой комплекс ОЛ-7', 'Серия «Оранжевое лето»', 'http://fun-terra.ru/catalog/seriya-oranzhevoe-leto/ol-07.html', 1, 1, 0),
(129, 'Новая модель', NULL, NULL, 1, 1, 0),
(130, 'Новая модель', NULL, NULL, 1, 1, 0),
(131, 'Новая модель', NULL, NULL, 1, 1, 0);

-- 
-- Восстановить предыдущий режим SQL (SQL mode)
-- 
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;

-- 
-- Включение внешних ключей
-- 
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;