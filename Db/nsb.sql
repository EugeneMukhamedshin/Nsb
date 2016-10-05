--
-- Скрипт сгенерирован Devart dbForge Studio for MySQL, Версия 7.1.13.0
-- Домашняя страница продукта: http://www.devart.com/ru/dbforge/mysql/studio
-- Дата скрипта: 06.10.2016 0:11:08
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
AUTO_INCREMENT = 64
AVG_ROW_LENGTH = 1024
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
  obj_filename VARCHAR(255) DEFAULT NULL,
  mtl_filename VARCHAR(255) DEFAULT NULL,
  add_ground INT(11) DEFAULT 1,
  is_valid INT(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (id)
)
ENGINE = INNODB
AUTO_INCREMENT = 98
AVG_ROW_LENGTH = 1820
CHARACTER SET utf8
COLLATE utf8_general_ci
COMMENT = 'Модели лабиринтов'
ROW_FORMAT = DYNAMIC;

-- 
-- Вывод данных для таблицы model_files
--
INSERT INTO model_files VALUES
(46, 2, 'zl-24_standart_mtl.mtl', 'zl-24 standart mtl.mtl', 2, 0, NULL),
(47, 2, 'веревка_bump.jpg', 'verevka bump.jpg', 3, 0, NULL),
(48, 2, 'ин.jpg', 'in.jpg', 3, 0, NULL),
(49, 2, 'ин2.jpg', 'in2.jpg', 3, 0, NULL),
(50, 2, 'птица прозр.png', 'ptitsa prozr.png', 3, 0, NULL),
(51, 2, 'птица.png', 'ptitsa.png', 3, 0, NULL),
(52, 2, 'стрекозный витраж.jpg', 'strekoznyy vitrazh.jpg', 3, 0, NULL),
(53, 2, 'цветовой круг иам.jpg', 'tsvetovoy krug iam.jpg', 3, 0, NULL),
(54, 2, 'zl-24_standart_mtl.obj', 'zl-24 standart mtl.obj', 1, 0, NULL),
(57, 2, 'bird.png', 'bird.png', 3, 0, NULL),
(58, 2, 'bird_opacity.png', 'bird opacity.png', 3, 0, NULL),
(59, 2, 'circle.jpg', 'circle.jpg', 3, 0, NULL),
(60, 2, 'strecoza.jpg', 'strecoza.jpg', 3, 0, NULL),
(61, 2, 'verevka_zel.jpg', 'verevka zel.jpg', 3, 0, NULL),
(62, 2, 'zl-24_new_mtl.mtl', 'zl-24 new mtl.mtl', 2, 0, NULL),
(63, 2, 'zl-24_new_mtl.obj', 'zl-24 new mtl.obj', 1, 0, NULL);

-- 
-- Вывод данных для таблицы models
--
INSERT INTO models VALUES
(2, 'male22', 'male02.obj', 'male02_dds.mtl', 1, 0),
(3, 'female', 'female02.obj', 'female02.mtl', 1, 0),
(4, 'walthead', 'WaltHead.obj', 'WaltHead.mtl', 1, 0),
(27, 'nupogodi', 'nupogodi.obj', 'nupogodi.mtl', 1, 0),
(28, 'kolibri', 'kolibri.obj', 'kolibri.mtl', 1, 0),
(31, 'Детская площадка zl24', 'ZL-24.obj', 'ZL-24.mtl', 1, 0),
(33, 'zl-24_standart_mtl', 'zl-24_standart_mtl.obj', 'zl-24_standart_mtl.mtl', 1, 0),
(34, 'mk10', 'mk10.obj', 'mk10.mtl', 1, 0),
(35, 'IL-14', 'IL-14.obj', 'IL-14.mtl', 1, 0),
(36, 'OL-1', 'ol-01.obj', 'ol-01.mtl', 1, 0),
(37, 'MIF-67', 'mif-67.obj', 'mif-67.mtl', 1, 0),
(38, 'ZL-13', 'zl-13.obj', 'zl-13.mtl', 1, 0),
(39, 'zl-32_original', 'zl-32_original.obj', 'zl-32_original.mtl', 1, 0),
(40, 'zl-32_other', 'zl-32_other.obj', 'zl-32_other.mtl', 1, 0),
(41, 'zl-12_original', 'zl-12_original.obj', 'zl-12_original.mtl', 1, 0),
(50, 'Новая модель', NULL, NULL, 1, 0),
(51, 'Новая модель', NULL, NULL, 1, 0),
(52, 'Новая модель', NULL, NULL, 1, 0),
(53, 'Новая модель', NULL, NULL, 1, 0),
(54, 'Новая модель', NULL, NULL, 1, 0),
(64, 'Новая модель', NULL, NULL, 1, 0),
(65, 'Новая модель', NULL, NULL, 1, 0),
(66, 'Новая модель', NULL, NULL, 1, 0),
(67, 'Новая модель', NULL, NULL, 1, 0),
(68, 'Новая модель', NULL, NULL, 1, 0),
(69, 'Новая модель', NULL, NULL, 1, 0),
(70, 'Новая модель', NULL, NULL, 1, 0),
(71, 'Новая модель', NULL, NULL, 1, 0),
(72, 'Новая модель', NULL, NULL, 1, 0),
(73, 'Новая модель', NULL, NULL, 1, 0),
(77, 'Новая модель', NULL, NULL, 1, 0),
(78, 'Новая модель', NULL, NULL, 1, 0),
(79, 'Новая модель', NULL, NULL, 1, 0),
(80, 'Новая модель', NULL, NULL, 1, 0),
(81, 'Новая модель', NULL, NULL, 1, 0),
(82, 'Новая модель', NULL, NULL, 1, 0),
(91, 'Новая модель', NULL, NULL, 1, 0),
(92, 'Новая модель', NULL, NULL, 1, 0),
(93, 'Новая модель', NULL, NULL, 1, 0),
(94, 'Новая модель', NULL, NULL, 1, 0),
(97, 'Новая модель', NULL, NULL, 1, 0);

-- 
-- Восстановить предыдущий режим SQL (SQL mode)
-- 
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;

-- 
-- Включение внешних ключей
-- 
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;