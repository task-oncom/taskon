-- phpMyAdmin SQL Dump
-- version 3.3.7
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Сен 30 2011 г., 14:43
-- Версия сервера: 5.1.49
-- Версия PHP: 5.3.3-1ubuntu9.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `schneider-electric`
--

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `city_id` int(11) unsigned DEFAULT NULL COMMENT 'Город',
  `first_name` varchar(40) NOT NULL COMMENT 'Имя',
  `last_name` varchar(40) DEFAULT NULL COMMENT 'Фамилия',
  `patronymic` varchar(40) DEFAULT NULL COMMENT 'Отчество',
  `company` varchar(400) DEFAULT NULL COMMENT 'Компания',
  `post` varchar(200) DEFAULT NULL COMMENT 'Должность',
  `email` varchar(200) NOT NULL COMMENT 'Email',
  `phone` varchar(14) DEFAULT NULL COMMENT 'Телефон',
  `phone_ext` varchar(14) DEFAULT NULL COMMENT 'Телефон 2',
  `fax` varchar(14) DEFAULT NULL COMMENT 'Факс',
  `password` varchar(32) NOT NULL COMMENT 'Пароль',
  `birthdate` date DEFAULT NULL COMMENT 'Дата рождения',
  `gender` enum('man','woman') DEFAULT NULL COMMENT 'Пол',
  `address` varchar(400) DEFAULT NULL COMMENT 'Адрес',
  `status` enum('active','new','blocked') DEFAULT 'new' COMMENT 'Статус',
  `activate_code` varchar(32) DEFAULT NULL COMMENT 'Код активации',
  `activate_date` datetime DEFAULT NULL COMMENT 'Дата активации',
  `password_change_code` varchar(32) DEFAULT NULL,
  `password_change_date` datetime DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Удален',
  `date_delete` datetime DEFAULT NULL COMMENT 'Дата удаления',
  `date_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Зарегистрирован',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `city_id` (`city_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7777778 ;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `city_id`, `first_name`, `last_name`, `patronymic`, `company`, `post`, `email`, `phone`, `phone_ext`, `fax`, `password`, `birthdate`, `gender`, `address`, `status`, `activate_code`, `activate_date`, `password_change_code`, `password_change_date`, `is_deleted`, `date_delete`, `date_create`) VALUES
(1, 3, 'Дмитрий', 'Королев', 'Александрович', 'ООО "Арт Проект"', 'Генеральный директор', 'korolev@kupitsite.ru', '9090909', '8989787', '8099709', 'f5d1278e8109edd94e1e4197e04873b9', NULL, NULL, 'hjk', 'active', NULL, NULL, NULL, NULL, 0, NULL, '2012-01-01 00:00:00');

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
