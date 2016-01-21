-- phpMyAdmin SQL Dump
-- version 3.3.7
-- http://www.phpmyadmin.net
--
-- ����: localhost
-- ����� ��������: ��� 11 2011 �., 20:50
-- ������ �������: 5.1.49
-- ������ PHP: 5.3.3-1ubuntu9.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- ���� ������: `schneider-electric`
--

-- --------------------------------------------------------

--
-- ��������� ������� `testings`
--

CREATE TABLE IF NOT EXISTS `testings` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL COMMENT '��������',
  `minutes` tinyint(4) NOT NULL COMMENT '����� � ������� (����� �����)',
  `percentage_passing` tinyint(4) NOT NULL COMMENT '����� ����������� (����� �����)',
  `questions_count` tinyint(4) NOT NULL COMMENT '���������� �������� (����� �����)',
  `date_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '�������',
  `date_update` datetime DEFAULT NULL COMMENT '���������',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- ���� ������ ������� `testings`
--


-- --------------------------------------------------------

--
-- ��������� ������� `testings_answers`
--

CREATE TABLE IF NOT EXISTS `testings_answers` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `question_id` int(11) unsigned NOT NULL COMMENT '������',
  `text` text NOT NULL COMMENT '�����',
  `is_correct` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '����������',
  `date_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '�������',
  `date_update` datetime NOT NULL COMMENT '���������',
  PRIMARY KEY (`id`),
  KEY `question_id` (`question_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- ���� ������ ������� `testings_answers`
--


-- --------------------------------------------------------

--
-- ��������� ������� `testings_questions`
--

CREATE TABLE IF NOT EXISTS `testings_questions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `section_id` int(11) unsigned DEFAULT NULL COMMENT '������',
  `difficulty` enum('hard','easy') NOT NULL COMMENT '���������',
  `type` enum('one_option','several_options','own_version') NOT NULL COMMENT '��� �������',
  `text` text NOT NULL COMMENT '�����',
  `image` varchar(37) DEFAULT NULL COMMENT '�����������',
  `is_active` tinyint(1) NOT NULL DEFAULT '0' COMMENT '�������',
  `date_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '������',
  `date_update` datetime NOT NULL COMMENT '��������',
  PRIMARY KEY (`id`),
  KEY `section_id` (`section_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- ���� ������ ������� `testings_questions`
--


-- --------------------------------------------------------

--
-- ��������� ������� `testings_sections`
--

CREATE TABLE IF NOT EXISTS `testings_sections` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) unsigned DEFAULT NULL COMMENT '��������',
  `name` varchar(150) NOT NULL COMMENT '��������',
  `is_active` tinyint(1) NOT NULL COMMENT '�������',
  `date_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '�������',
  `date_update` datetime NOT NULL COMMENT '���������',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- ���� ������ ������� `testings_sections`
--


--
-- ����������� �������� ����� ����������� ������
--

--
-- ����������� �������� ����� ������� `testings_answers`
--
ALTER TABLE `testings_answers`
  ADD CONSTRAINT `testings_answers_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `testings_questions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- ����������� �������� ����� ������� `testings_questions`
--
ALTER TABLE `testings_questions`
  ADD CONSTRAINT `testings_questions_ibfk_1` FOREIGN KEY (`section_id`) REFERENCES `testings_sections` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- ����������� �������� ����� ������� `testings_sections`
--
ALTER TABLE `testings_sections`
  ADD CONSTRAINT `testings_sections_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `testings_sections` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
