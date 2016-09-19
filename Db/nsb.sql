--
-- Скрипт сгенерирован Devart dbForge Studio for MySQL, Версия 7.1.13.0
-- Домашняя страница продукта: http://www.devart.com/ru/dbforge/mysql/studio
-- Дата скрипта: 19.09.2016 0:26:18
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
-- Описание для таблицы models
--
DROP TABLE IF EXISTS models;
CREATE TABLE models (
  id INT(11) NOT NULL AUTO_INCREMENT,
  name VARCHAR(50) NOT NULL,
  obj_filename VARCHAR(255) NOT NULL,
  mtl_filename VARCHAR(255) NOT NULL,
  add_ground INT(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (id)
)
ENGINE = INNODB
AUTO_INCREMENT = 42
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
-- Вывод данных для таблицы models
--
INSERT INTO models VALUES
(1, 'male', 'male02.obj', 'male02_dds.mtl', 1),
(2, 'male22', 'male02.obj', 'male02_dds.mtl', 1),
(3, 'female', 'female02.obj', 'female02.mtl', 1),
(4, 'walthead', 'WaltHead.obj', 'WaltHead.mtl', 1),
(27, 'nupogodi', 'nupogodi.obj', 'nupogodi.mtl', 1),
(28, 'kolibri', 'kolibri.obj', 'kolibri.mtl', 1),
(31, 'Детская площадка zl24', 'ZL-24.obj', 'ZL-24.mtl', 1),
(33, 'zl-24_standart_mtl', 'zl-24_standart_mtl.obj', 'zl-24_standart_mtl.mtl', 1),
(34, 'mk10', 'mk10.obj', 'mk10.mtl', 1),
(35, 'IL-14', 'IL-14.obj', 'IL-14.mtl', 1),
(36, 'OL-1', 'ol-01.obj', 'ol-01.mtl', 1),
(37, 'MIF-67', 'mif-67.obj', 'mif-67.mtl', 1),
(38, 'ZL-13', 'zl-13.obj', 'zl-13.mtl', 1),
(39, 'zl-32_original', 'zl-32_original.obj', 'zl-32_original.mtl', 1),
(40, 'zl-32_other', 'zl-32_other.obj', 'zl-32_other.mtl', 1),
(41, 'zl-12_original', 'zl-12_original.obj', 'zl-12_original.mtl', 1);

-- 
-- Восстановить предыдущий режим SQL (SQL mode)
-- 
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;

-- 
-- Включение внешних ключей
-- 
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;