--
-- Скрипт сгенерирован Devart dbForge Studio for MySQL, Версия 7.1.13.0
-- Домашняя страница продукта: http://www.devart.com/ru/dbforge/mysql/studio
-- Дата скрипта: 25.08.2016 23:41:53
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
  PRIMARY KEY (id)
)
ENGINE = INNODB
AUTO_INCREMENT = 29
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
(1, 'male', 'male02.obj', 'male02_dds.mtl'),
(2, 'male22', 'male02.obj', 'male02_dds.mtl'),
(3, 'female', 'female02.obj', 'female02.mtl'),
(4, 'walthead', 'WaltHead.obj', 'WaltHead.mtl'),
(23, 'nupogodi', 'nupogodi.obj', 'nupogodi.mtl'),
(24, 'cognac', 'cognac.obj', 'cognac.mtl'),
(25, 'table', 'table.obj', 'table.mtl'),
(26, 'falcon', 'falcon.obj', 'falcon.mtl'),
(27, 'nupogodi', 'nupogodi.obj', 'nupogodi.mtl'),
(28, 'kolibri', 'kolibri.obj', 'kolibri.mtl');

-- 
-- Восстановить предыдущий режим SQL (SQL mode)
-- 
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;

-- 
-- Включение внешних ключей
-- 
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;