-- phpMyAdmin SQL Dump
-- version 3.3.7
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Окт 11 2011 г., 20:50
-- Версия сервера: 5.1.49
-- Версия PHP: 5.3.3-1ubuntu9.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- База данных: `schneider-electric`
--

-- --------------------------------------------------------

--
-- Структура таблицы `testings`
--

CREATE TABLE IF NOT EXISTS `testings` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL COMMENT 'Название',
  `minutes` tinyint(4) NOT NULL COMMENT 'Время в минутах (целое число)',
  `percentage_passing` tinyint(4) NOT NULL COMMENT 'Лимит прохождения (целое число)',
  `questions_count` tinyint(4) NOT NULL COMMENT 'Количество вопросов (целое число)',
  `date_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Создано',
  `date_update` datetime DEFAULT NULL COMMENT 'Обновлено',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `testings`
--


-- --------------------------------------------------------

--
-- Структура таблицы `testings_answers`
--

CREATE TABLE IF NOT EXISTS `testings_answers` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `question_id` int(11) unsigned NOT NULL COMMENT 'Вопрос',
  `text` text NOT NULL COMMENT 'Текст',
  `is_correct` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'Правильный',
  `date_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Создано',
  `date_update` datetime NOT NULL COMMENT 'Обновлено',
  PRIMARY KEY (`id`),
  KEY `question_id` (`question_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `testings_answers`
--


-- --------------------------------------------------------

--
-- Структура таблицы `testings_questions`
--

CREATE TABLE IF NOT EXISTS `testings_questions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `section_id` int(11) unsigned DEFAULT NULL COMMENT 'Раздел',
  `difficulty` enum('hard','easy') NOT NULL COMMENT 'Сложность',
  `type` enum('one_option','several_options','own_version') NOT NULL COMMENT 'Тип вопроса',
  `text` text NOT NULL COMMENT 'Текст',
  `image` varchar(37) DEFAULT NULL COMMENT 'Изображение',
  `is_active` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Активен',
  `date_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Создан',
  `date_update` datetime NOT NULL COMMENT 'Обновлен',
  PRIMARY KEY (`id`),
  KEY `section_id` (`section_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `testings_questions`
--


-- --------------------------------------------------------

--
-- Структура таблицы `testings_sections`
--

CREATE TABLE IF NOT EXISTS `testings_sections` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) unsigned DEFAULT NULL COMMENT 'Родитель',
  `name` varchar(150) NOT NULL COMMENT 'Название',
  `is_active` tinyint(1) NOT NULL COMMENT 'Активен',
  `date_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Создано',
  `date_update` datetime NOT NULL COMMENT 'Обновлено',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `testings_sections`
--


--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `testings_answers`
--
ALTER TABLE `testings_answers`
  ADD CONSTRAINT `testings_answers_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `testings_questions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `testings_questions`
--
ALTER TABLE `testings_questions`
  ADD CONSTRAINT `testings_questions_ibfk_1` FOREIGN KEY (`section_id`) REFERENCES `testings_sections` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `testings_sections`
--
ALTER TABLE `testings_sections`
  ADD CONSTRAINT `testings_sections_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `testings_sections` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
