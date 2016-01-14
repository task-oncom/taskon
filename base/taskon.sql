-- phpMyAdmin SQL Dump
-- version 4.0.10.6
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1:3306
-- Время создания: Янв 13 2016 г., 23:26
-- Версия сервера: 5.5.41-log
-- Версия PHP: 5.5.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `mfo`
--

-- --------------------------------------------------------

--
-- Структура таблицы `auth_assignment`
--

CREATE TABLE IF NOT EXISTS `auth_assignment` (
  `item_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`item_name`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `auth_assignment`
--

INSERT INTO `auth_assignment` (`item_name`, `user_id`, `created_at`) VALUES
('content', '74', 1428753802),
('content', '77', 1425795583),
('faq', '77', 1425795583),
('main', '77', 1425795583),
('rbac', '77', 1425795583),
('request', '77', 1430890568),
('request', '81', 1433510564),
('reviews', '77', 1428751742),
('scoring', '77', 1425795583),
('superadmin', '77', 1425110198),
('tarifs', '77', 1432054985),
('units', '77', 1425795583),
('users', '77', 1425795583);

-- --------------------------------------------------------

--
-- Структура таблицы `auth_item`
--

CREATE TABLE IF NOT EXISTS `auth_item` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `type` int(11) NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `rule_name` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` text COLLATE utf8_unicode_ci,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`),
  KEY `rule_name` (`rule_name`),
  KEY `idx-auth_item-type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `auth_item`
--

INSERT INTO `auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES
('admin', 2, 'Admin', 'group', NULL, 1425110198, 1425110198),
('content', 2, 'Контент', NULL, NULL, 1425799322, 1425799322),
('faq', 2, 'Вопрос-ответ', NULL, NULL, 1425799322, 1425799322),
('main', 2, 'Настройки системы', NULL, NULL, 1425799322, 1425799322),
('moderator ', 2, 'Moderator ', 'group', NULL, 1425110198, 1425110198),
('rbac', 2, 'Управление доступом', NULL, NULL, 1425799322, 1425799322),
('request', 2, 'Кредитование', NULL, NULL, 1430890568, 1430890568),
('reviews', 2, 'Отзывы', NULL, NULL, 1428751742, 1428751742),
('scoring', 2, 'Скоринг', NULL, NULL, 1425799322, 1425799322),
('superadmin', 2, 'Superadmin', 'group', NULL, 1425110198, 1425110198),
('tarifs', 2, 'Кредитные тарифы', NULL, NULL, 1432054985, 1432054985),
('units', 2, 'Модули', NULL, NULL, 1425799322, 1425799322),
('user', 2, 'User', 'group', NULL, 1425110198, 1425110198),
('user-admin_manage', 0, 'Управление пользователями', NULL, NULL, NULL, NULL),
('users', 2, 'Кредитование', NULL, NULL, 1425799322, 1425799322);

-- --------------------------------------------------------

--
-- Структура таблицы `auth_item_child`
--

CREATE TABLE IF NOT EXISTS `auth_item_child` (
  `parent` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `child` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `auth_item_child`
--

INSERT INTO `auth_item_child` (`parent`, `child`) VALUES
('superadmin', 'admin'),
('admin', 'moderator '),
('moderator ', 'user');

-- --------------------------------------------------------

--
-- Структура таблицы `auth_rule`
--

CREATE TABLE IF NOT EXISTS `auth_rule` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `data` text COLLATE utf8_unicode_ci,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `auth_rule`
--

INSERT INTO `auth_rule` (`name`, `data`, `created_at`, `updated_at`) VALUES
('group', 'O:40:"common\\modules\\rbac\\components\\GroupRule":3:{s:4:"name";s:5:"group";s:9:"createdAt";i:1425110198;s:9:"updatedAt";i:1425110198;}', 1425110198, 1425110198);

-- --------------------------------------------------------

--
-- Структура таблицы `co_blocks`
--

CREATE TABLE IF NOT EXISTS `co_blocks` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `lang` char(2) DEFAULT 'ru' COMMENT 'Язык',
  `module` varchar(100) DEFAULT NULL COMMENT 'Модуль',
  `title` varchar(250) NOT NULL COMMENT 'Заголовок',
  `name` varchar(50) NOT NULL COMMENT 'Название (англ.)',
  `text` longtext NOT NULL COMMENT 'Текст',
  `date_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Добавлено',
  `created_at` int(15) DEFAULT NULL,
  `updated_at` int(15) DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `lang_2` (`lang`,`title`),
  UNIQUE KEY `lang_3` (`lang`,`name`),
  KEY `lang` (`lang`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=26 ;

-- --------------------------------------------------------

--
-- Структура таблицы `co_category`
--

CREATE TABLE IF NOT EXISTS `co_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `co_category`
--

INSERT INTO `co_category` (`id`, `name`, `url`, `created_at`, `updated_at`) VALUES
(2, 'Главная страница', '1', 1428904215, 1435666830);

-- --------------------------------------------------------

--
-- Структура таблицы `co_content`
--

CREATE TABLE IF NOT EXISTS `co_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) DEFAULT NULL,
  `url` varchar(250) NOT NULL,
  `name` varchar(250) NOT NULL,
  `title` varchar(250) DEFAULT NULL,
  `text` longtext,
  `active` int(1) DEFAULT '0',
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `category` (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=25 ;

--
-- Дамп данных таблицы `co_content`
--

INSERT INTO `co_content` (`id`, `category_id`, `url`, `name`, `title`, `text`, `active`, `created_at`, `updated_at`) VALUES
(23, NULL, 'site/error', '404', '404', '<div class="layout">{menu-other}<main class="main">{err404}</main><!-- /main --><footer class="footer">\r\n<div class="container-fluid">{footer}<hr />\r\n<div class="row">\r\n<div class="col-sm-10">{copyright}</div>\r\n<!-- /col-sm-10 -->\r\n<div class="col-sm-2"><span class="age-18">18 +</span><!-- /age-18 --></div>\r\n<!-- /col-sm-2 --></div>\r\n<!-- /row --></div>\r\n<!-- /container-fluid --></footer><!-- /footer --></div>', 0, NULL, NULL),
(24, 2, '/', 'Главная', 'Главная', 'Главная', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `co_content_data`
--

CREATE TABLE IF NOT EXISTS `co_content_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content_id` int(11) DEFAULT NULL,
  `title` varchar(250) DEFAULT NULL,
  `short_description` varchar(255) DEFAULT NULL,
  `description` text,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `content` (`content_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `faq`
--

CREATE TABLE IF NOT EXISTS `faq` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lang` varchar(2) NOT NULL DEFAULT 'ru' COMMENT 'Language',
  `name` varchar(255) DEFAULT NULL COMMENT 'First Name',
  `last_name` varchar(255) DEFAULT NULL COMMENT 'Last Name',
  `patronymic` varchar(255) DEFAULT NULL COMMENT 'Patronymic',
  `phone` varchar(50) DEFAULT NULL COMMENT 'Phone',
  `email` varchar(80) DEFAULT NULL COMMENT 'Email',
  `cat_id` int(11) DEFAULT NULL COMMENT 'Category',
  `question` longtext COMMENT 'Question',
  `answer` longtext COMMENT 'Answer',
  `is_published` tinyint(1) DEFAULT '0' COMMENT 'Published',
  `welcome` varchar(255) DEFAULT NULL COMMENT 'Welcome',
  `notification_date` date DEFAULT NULL COMMENT 'Notify Date',
  `notification_send` tinyint(1) DEFAULT '0' COMMENT 'Notify Sended',
  `order` int(5) DEFAULT NULL COMMENT 'Order',
  `show_in_module` varchar(255) DEFAULT NULL COMMENT 'Show In Module',
  `view_count` int(11) DEFAULT '0' COMMENT 'View Count',
  `url` varchar(255) DEFAULT NULL COMMENT 'Url',
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `faq_category`
--

CREATE TABLE IF NOT EXISTS `faq_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT 'Категория',
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`,`name`),
  KEY `category_id` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `faq_category`
--

INSERT INTO `faq_category` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Основная', NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `meta_tags`
--

CREATE TABLE IF NOT EXISTS `meta_tags` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `object_id` int(11) unsigned NOT NULL COMMENT 'ID объекта',
  `model_id` varchar(50) NOT NULL COMMENT 'Модель',
  `language` varchar(5) NOT NULL COMMENT 'Язык',
  `title` varchar(300) NOT NULL COMMENT 'Заголовок',
  `keywords` varchar(300) NOT NULL COMMENT 'Ключевые слова',
  `description` varchar(300) NOT NULL COMMENT 'Описание',
  `created_at` int(11) DEFAULT NULL COMMENT 'Создано',
  `updated_at` int(11) DEFAULT NULL COMMENT 'Отредактирован',
  PRIMARY KEY (`id`),
  UNIQUE KEY `object_id` (`object_id`,`model_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=37 ;

--
-- Дамп данных таблицы `meta_tags`
--

INSERT INTO `meta_tags` (`id`, `object_id`, `model_id`, `language`, `title`, `keywords`, `description`, `created_at`, `updated_at`) VALUES
(6, 1, 'common\\modules\\content\\models\\CoContent', 'ru', 'Это главная', 'Это главная', 'Это главная', 1427531082, 1435822414),
(36, 24, 'common\\modules\\content\\models\\CoContent', 'ru', '', '', '', 1450859568, 1450859568),
(5, 6, 'common\\modules\\content\\models\\CoContent', 'ru', 'тест', 'тест', 'тест', 1427531082, 1432196320),
(10, 20, 'common\\modules\\content\\models\\CoContent', 'ru', 'Как это работает? - Онлайн выдача займов', 'как это работает', 'как это работает', 1430320024, 1432062111),
(11, 1, 'common\\modules\\faq\\models\\Faq', 'ru', 'Получение займа', 'контакт, система контакт, поступили деньги, как узнать, получение перевода, kontakt', 'Круглосуточная выдача займов нашим клиентам. Быстрые переводы через Контакт.', 1430828849, 1432644694),
(12, 3, 'common\\modules\\faq\\models\\Faq', 'ru', 'Займы под низкий процент круглосуточно', 'займы, погашение, низкий процент, низкий %, займ дешево, взять в долг, ', 'Уменьшение процентной ставки нашим постоянным клиентам. Постоянные акции по снижению процентов. Звоните!', 1430829119, 1430983831),
(13, 4, 'common\\modules\\faq\\models\\Faq', 'ru', 'Погашение займа ', 'погашение займа, возврат займа, вернуть долг, отдать займ, ', 'Возвращайте займы удобным для вас способом. Множество платежных систем. Выгодно и удобно.', 1430829544, 1430984105),
(14, 6, 'common\\modules\\faq\\models\\Faq', 'ru', 'Погашение займа через Киви', 'киви, qiwi, вернуть через киви, возврат без комиссии, быстрый, возврат, займа, ', 'Без комиссии возвращайте займы через платежную систему Киви. Просто и удобно. Никаких дополнительных переплат.', 1430829726, 1430984342),
(15, 7, 'common\\modules\\faq\\models\\Faq', 'ru', 'Займ за границей', 'займ за пределами РФ, за границей, взять, деньги, в другой стране, находясь,', 'Мы выдаем займы нашим клиентам с паспортом РФ в любых странах. Мгновенные переводы через различные платежные системы. Быстро и удобно.', 1430829814, 1430984804),
(16, 8, 'common\\modules\\faq\\models\\Faq', 'ru', 'Начисление пени', 'пени за просрочку, расчет пени, размер пени, ставка пени, пени, просрочка, штрафы, не вернул вовремя, начисление пени, пеней, ', 'Ставка пени минимальная. При погашении займа в срок или при досрочном погашении пени не начисляются. ', 1430829889, 1430985833),
(17, 9, 'common\\modules\\faq\\models\\Faq', 'ru', '', '', '', 1430829976, 1430830639),
(18, 30, 'common\\modules\\faq\\models\\Faq', 'ru', '', '', '', 1430830077, 1430830647),
(19, 31, 'common\\modules\\faq\\models\\Faq', 'ru', '', '', '', 1430830261, 1430830261),
(20, 35, 'common\\modules\\faq\\models\\Faq', 'ru', '', '', '', 1430830331, 1430830626),
(21, 36, 'common\\modules\\faq\\models\\Faq', 'ru', '', '', '', 1430830383, 1430830607),
(22, 37, 'common\\modules\\faq\\models\\Faq', 'ru', '', '', '', 1430830445, 1430830601),
(23, 38, 'common\\modules\\faq\\models\\Faq', 'ru', '', '', '', 1430830565, 1430830565),
(24, 39, 'common\\modules\\faq\\models\\Faq', 'ru', '', '', '', 1430830714, 1430830714),
(25, 40, 'common\\modules\\faq\\models\\Faq', 'ru', 'Title', 'Keywords,Keywords', 'Description', 1430830763, 1432738488),
(26, 41, 'common\\modules\\faq\\models\\Faq', 'ru', '', '', '', 1430830793, 1432203673),
(27, 42, 'common\\modules\\faq\\models\\Faq', 'ru', '', '', '', 1430830847, 1430830847),
(28, 43, 'common\\modules\\faq\\models\\Faq', 'ru', '', '', '', 1430830894, 1430830894),
(29, 44, 'common\\modules\\faq\\models\\Faq', 'ru', '', '', '', 1430830956, 1430830956),
(30, 45, 'common\\modules\\faq\\models\\Faq', 'ru', '', '', '', 1430831089, 1430831089),
(31, 46, 'common\\modules\\faq\\models\\Faq', 'ru', '', '', '', 1430832030, 1430832030),
(32, 47, 'common\\modules\\faq\\models\\Faq', 'ru', '', '', '', 1430832533, 1432644664),
(34, 22, 'common\\modules\\content\\models\\CoContent', 'ru', 'Требования к ПО', 'по, требования, работа с сайтом, технические требования, системные требования', 'Технические требования для работы с сайтом ', 1430906924, 1430922337),
(35, 23, 'common\\modules\\content\\models\\CoContent', 'ru', '404 страница не найдена', '404 страница не найдена', '404 страница не найдена', 1432895790, 1432895790);

-- --------------------------------------------------------

--
-- Структура таблицы `migration`
--

CREATE TABLE IF NOT EXISTS `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `migration`
--

INSERT INTO `migration` (`version`, `apply_time`) VALUES
('m000000_000000_base', 1425107497),
('m140506_102106_rbac_init', 1425107902);

-- --------------------------------------------------------

--
-- Структура таблицы `reviews`
--

CREATE TABLE IF NOT EXISTS `reviews` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `lang` char(2) DEFAULT 'ru' COMMENT 'Язык',
  `user_id` int(11) unsigned NOT NULL COMMENT 'Автор',
  `title` varchar(250) NOT NULL COMMENT 'Имя',
  `text` longtext NOT NULL COMMENT 'Текст',
  `good` longtext,
  `bad` longtext,
  `admin_id` int(11) DEFAULT NULL COMMENT 'Ответил',
  `answer` longtext COMMENT 'Ответ',
  `photo` varchar(50) DEFAULT NULL COMMENT 'Фото',
  `state` enum('active','hidden') NOT NULL COMMENT 'Состояние',
  `date` date NOT NULL COMMENT 'Дата',
  `date_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Создана',
  `priority` int(11) DEFAULT NULL,
  `email` varchar(255) NOT NULL COMMENT 'Email',
  `notification_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `notification_send` tinyint(1) NOT NULL,
  `order` int(11) NOT NULL,
  `attendant_products` text NOT NULL,
  `cat_id` int(11) DEFAULT NULL COMMENT 'Публиковать на странице категории',
  `show_in_module` int(11) NOT NULL COMMENT 'Отображать на Главной',
  `rate_usability` int(1) DEFAULT '0' COMMENT 'Рейтинг - удобство',
  `rate_loyality` int(1) DEFAULT '0' COMMENT 'Рейтинг - лояльность',
  `rate_profit` int(1) DEFAULT '0' COMMENT 'Рейтинг - выгода',
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `lang` (`lang`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=698 ;

-- --------------------------------------------------------

--
-- Структура таблицы `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `module_id` varchar(50) NOT NULL COMMENT 'Модуль',
  `code` varchar(50) NOT NULL COMMENT 'Код',
  `name` varchar(100) NOT NULL COMMENT 'Заголовок',
  `value` text NOT NULL COMMENT 'Значение',
  `element` enum('text','textarea','editor') NOT NULL COMMENT 'Элемент',
  `hidden` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Скрыта',
  `description` varchar(2550) NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `const` (`code`),
  UNIQUE KEY `title` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=100 ;

--
-- Дамп данных таблицы `settings`
--

INSERT INTO `settings` (`id`, `module_id`, `code`, `name`, `value`, `element`, `hidden`, `description`, `created_at`, `updated_at`) VALUES
(82, 'scoring', 'scoring_high', 'Максимальное количество баллов2', '170', '', 0, 'Максимальное количество баллов2', NULL, 1425467407),
(83, 'scoring', 'scoring_low', 'Минимальное количество баллов1', '20', '', 0, 'describe', NULL, 1425467339),
(85, 'faq', 'email-publish-new-question', 'Адрес для e-mail уведомлений о публикации нового вопроса', 'test', 'text', 0, 'Адрес для e-mail уведомлений о публикации нового вопроса', 1427138025, 1427138025),
(86, 'reviews', 'email-new-review', 'Адрес для e-mail уведомлений о поступлении нового отзыва на модерацию', 'test@mail.ru', 'text', 0, 'Адрес для e-mail уведомлений о поступлении нового отзыва на модерацию', 1427138429, 1427138429),
(87, 'content', 'content-phone', 'Телефонный номер', '8-800-333-72-41', '', 0, 'Телефонный номер', 1429012057, 1433528585),
(88, 'content', 'content-email', 'Контактный e-mail адрес ', 'test@mail.ru', '', 0, 'Контактный e-mail адрес ', 1429012089, 1429012089),
(89, 'content', 'content-support-email', 'E-mail службы поддержки - отображается в отправляемых e-mail письмах', 'test@mail.ru', '', 0, 'E-mail службы поддержки - отображается в отправляемых e-mail письмах', 1429012135, 1429012135),
(90, 'content', 'content-work-time', 'Время работы компании', '09:00 - 18:00', '', 0, 'Время работы компании', 1429012196, 1429012196),
(91, 'tarifs', 'penya', 'Пеня', '0.05', 'text', 0, 'Пеня (например 0.05)', 1431596925, 1431596925),
(92, 'tarifs', 'fine', 'Штраф', '500', 'text', 0, 'Единоразовый штраф', 1431598415, 1431598415),
(93, 'units', 'sms-password', 'Текст смс с паролем', 'Ваш пароль для входа в личный кабинет: [password]', 'text', 0, '[password] - заменяется на сгенерированный пароль', 1432287762, 1432288026),
(94, 'units', 'sms-code', 'Текст смс с кодом', 'Код подтверждения: [code]. Сессия: [session]', 'text', 0, '[code] и [session] заменяются на сгенерированные данные', 1432288017, 1432288017),
(96, 'request', 'sms-approve', 'CМС с кодом подтверждения получения', 'Ваша займ одобрен. Код подтверждения: [code]. Для подтверждения зайдите в личный кабинет http://soc-zaim.ru/lk', '', 0, 'CМС с кодом подтверждения получения', 1433504089, 1433504089),
(97, 'request', 'sms-password-restore', 'SMS с восстановленным паролем', 'Ваш новый пароль для входа в личный кабинет: [password]', '', 0, 'SMS с восстановленным паролем', 1433787513, 1433787513),
(98, 'main', 'support_email', 'Email администратора', 'thenewsit@gmail.com', '', 0, 'Email администратора', 1433956486, 1433956486),
(99, 'request', 'email-request-create', 'Новая заявка на займ - {number}', 'Создана новая заявка на получение займа:\n\nФИО: {fio}\nДата: {date}\nСумма: {sum}\nСрок: {day}', 'editor', 0, 'Email уведомление менеджеров при создании заявки', NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `fio` varchar(200) DEFAULT NULL COMMENT 'ФИО',
  `name` varchar(255) DEFAULT NULL,
  `surname` varchar(255) DEFAULT NULL,
  `email` varchar(200) NOT NULL COMMENT 'Email',
  `phone` varchar(14) DEFAULT NULL COMMENT 'Телефон',
  `mobile_phone` varchar(14) DEFAULT NULL COMMENT 'Мобильный телефон',
  `skype` varchar(20) DEFAULT NULL COMMENT 'Skype',
  `password` varchar(62) NOT NULL COMMENT 'Пароль',
  `status` enum('active','new','blocked') DEFAULT 'new' COMMENT 'Статус',
  `activate_code` varchar(32) DEFAULT NULL COMMENT 'Код активации',
  `activate_date` datetime DEFAULT NULL COMMENT 'Дата активации',
  `password_change_code` varchar(32) DEFAULT NULL,
  `password_change_date` datetime DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Удален',
  `date_delete` datetime DEFAULT NULL COMMENT 'Дата удаления',
  `date_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Зарегистрирован',
  `ICQ` int(11) DEFAULT NULL COMMENT 'ICQ',
  `post` varchar(60) DEFAULT NULL COMMENT 'Должность',
  `auth_key` varchar(32) NOT NULL,
  `sort` int(10) DEFAULT '0',
  `role` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=82 ;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `fio`, `name`, `surname`, `email`, `phone`, `mobile_phone`, `skype`, `password`, `status`, `activate_code`, `activate_date`, `password_change_code`, `password_change_date`, `is_deleted`, `date_delete`, `date_create`, `ICQ`, `post`, `auth_key`, `sort`, `role`) VALUES
(77, 'Друппов Андрей', NULL, NULL, 'admin@task-on.com', '5gt45', NULL, '4rtg', '$2y$13$cyluwSX52NZ/OSPJIWCEjua4NXdjbBkwTi1vm20E5iLOKMNLY5ub2', 'active', 'e12a52c0dff476b140f79bfbb8ad2a8d', NULL, NULL, NULL, 0, '2014-09-10 11:57:24', '2014-08-12 20:17:15', 43, NULL, 'Cae8WmdkwECBOKIODDZ5OfQHvN-8JmPn', 5, 'admin');

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `co_content`
--
ALTER TABLE `co_content`
  ADD CONSTRAINT `category` FOREIGN KEY (`category_id`) REFERENCES `co_category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `co_content_data`
--
ALTER TABLE `co_content_data`
  ADD CONSTRAINT `content` FOREIGN KEY (`content_id`) REFERENCES `co_content` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
