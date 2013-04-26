-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Mar 02 Avril 2013 à 18:22
-- Version du serveur: 5.5.24-log
-- Version de PHP: 5.4.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `db_slashcms`
--

-- --------------------------------------------------------

--
-- Structure de la table `admmenu`
--

CREATE TABLE IF NOT EXISTS `admmenu` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(50) NOT NULL,
  `parent` int(11) unsigned NOT NULL,
  `position` int(11) unsigned NOT NULL,
  `title_fr` varchar(255) NOT NULL,
  `title_en` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `action` varchar(255) NOT NULL,
  `enabled` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `enabled` (`enabled`),
  KEY `position` (`position`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=50 ;

--
-- Contenu de la table `admmenu`
--

INSERT INTO `admmenu` (`id`, `type`, `parent`, `position`, `title_fr`, `title_en`, `icon`, `action`, `enabled`) VALUES
(1, 'none', 0, 1, 'Site', 'Website', 'site.png', '#', 1),
(2, 'none', 0, 3, 'Configuration', 'Config', 'config.png', '#', 1),
(3, 'url_self', 1, 3, 'Gestion des articles', 'Articles', 'articles.png', 'index.php?mod=sla_articles', 1),
(6, 'url_self', 1, 1, 'Tableau de bord', 'Main panel', 'panel.png', 'index.php?mod=sla_panel', 1),
(10, 'none', 0, 2, 'Modules', 'Modules', 'modules.png', '#', 1),
(11, 'url_self', 1, 4, 'Gestion des news', 'News', 'news.png', 'index.php?mod=sla_news', 1),
(12, 'url_self', 2, 1, 'Gestion des th&egrave;mes', 'Templates config', 'template.png', 'index.php?mod=sla_template', 0),
(14, 'none', 0, 4, 'Aide', 'Help', 'help.png', '#', 1),
(15, 'url_self', 14, 1, 'Support technique', 'Technical support', 'support.png', '', 1),
(16, 'url_self', 1, 5, 'Gestion des menus', 'Menus config', 'menus.png', 'index.php?mod=sla_menu', 1),
(17, 'url_self', 2, 2, 'Gestion utilisateurs', 'Users settings', 'users.png', 'index.php?mod=sla_users', 1),
(19, 'url_self', 1, 2, 'Gestion des cat&eacute;gories', 'Categories', 'categories.png', 'index.php?mod=sla_categories', 1),
(23, 'url_self', 2, 3, 'Gestion des modules BETA', 'Modules configuration', 'config2.png', 'index.php?mod=sla_modules', 1),
(36, 'url_self', 10, 2, 'Gestion des pages', 'Pages', 'page.png', 'index.php?mod=sla_pages', 1),
(38, 'url_self', 2, 4, 'Gestion des Pays', 'Country management', 'country.png', 'index.php?mod=sla_country', 1),
(39, 'url_self', 2, 5, 'Gestion des Langues', 'Languages management', 'lang.png', 'index.php?mod=sla_lang', 1),
(47, 'url_self', 2, 6, 'Editer la configuration', 'Config', 'config.png', 'index.php?mod=sla_config', 1),
(48, 'url_self', 10, 1, 'M&eacute;diath&egrave;que', 'Medias', 'media.png', 'index.php?mod=sla_medias', 1),
(49, 'url_self', 2, 7, 'Gestion des logs', 'Logs configs', 'table_multiple.png', 'index.php?mod=sla_logs', 1);

-- --------------------------------------------------------

--
-- Structure de la table `articles`
--

CREATE TABLE IF NOT EXISTS `articles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(11) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `alias` varchar(255) NOT NULL,
  `meta_description` varchar(255) NOT NULL,
  `meta_keywords` varchar(255) NOT NULL,
  `robots_index` tinyint(1) unsigned NOT NULL,
  `robots_follow` tinyint(1) unsigned NOT NULL,
  `created_date` datetime NOT NULL,
  `publish_date` datetime NOT NULL,
  `unpublish_date` datetime NOT NULL,
  `enabled` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `attachments`
--

CREATE TABLE IF NOT EXISTS `attachments` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(11) unsigned NOT NULL,
  `id_module` int(11) unsigned NOT NULL,
  `id_element` int(11) unsigned NOT NULL,
  `id_field` varchar(50) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `position` int(11) unsigned NOT NULL,
  `state` tinyint(4) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `position` (`position`),
  KEY `state` (`state`),
  KEY `id_element` (`id_element`),
  KEY `id_module` (`id_module`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(11) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `categories`
--

INSERT INTO `categories` (`id`, `id_user`, `title`, `description`) VALUES
(1, 1, 'Wiki', '<p>\r\n Articles du wiki</p>\r\n'),
(2, 1, 'Projet', '<p>\r\n Articles du projet</p>\r\n');

-- --------------------------------------------------------

--
-- Structure de la table `config`
--

CREATE TABLE IF NOT EXISTS `config` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `config_name` varchar(50) NOT NULL,
  `config_value` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Contenu de la table `config`
--

INSERT INTO `config` (`id`, `config_name`, `config_value`) VALUES
(1, 'site_name', 'slash cms'),
(2, 'global_description', 'slash : le cms intuitif'),
(3, 'global_keywords', 'cms, slash, slash-cms, siteweb'),
(4, 'seo_enabled', 'true'),
(5, 'slash_language', 'fr'),
(6, 'site_template_url', 'templates/slashcms/'),
(7, 'admin_template_url', 'templates/wd-admin/'),
(8, 'admin_email', 'weneedyou@slash-cms.com'),
(9, 'mobile_template_url', 'templates/default_mobile/'),
(10, 'mobile_detection', 'true');

-- --------------------------------------------------------

--
-- Structure de la table `country`
--

CREATE TABLE IF NOT EXISTS `country` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  `shortname` varchar(8) NOT NULL,
  `enabled` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `country`
--

INSERT INTO `country` (`id`, `name`, `shortname`, `enabled`) VALUES
(1, 'France', 'Fr', 1);

-- --------------------------------------------------------

--
-- Structure de la table `joins`
--

CREATE TABLE IF NOT EXISTS `joins` (
  `id_mod1` int(10) unsigned NOT NULL,
  `id_mod2` int(10) unsigned NOT NULL,
  `id_mod3` int(10) unsigned NOT NULL,
  `id1` int(10) unsigned NOT NULL,
  `id2` int(10) unsigned NOT NULL,
  `id3` int(10) unsigned NOT NULL,
  `item_data` varchar(255) NOT NULL,
  `item_order` int(10) unsigned NOT NULL,
  `item_enabled` tinyint(4) NOT NULL,
  KEY `id_mod1` (`id_mod1`,`id_mod2`,`id_mod3`),
  KEY `id_mod1_2` (`id_mod1`),
  KEY `id_mod2` (`id_mod2`),
  KEY `id_mod3` (`id_mod3`),
  KEY `id1` (`id1`),
  KEY `id2` (`id2`),
  KEY `id3` (`id3`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `joins`
--

INSERT INTO `joins` (`id_mod1`, `id_mod2`, `id_mod3`, `id1`, `id2`, `id3`, `item_data`, `item_order`, `item_enabled`) VALUES
(13, 7, 0, 3, 2, 0, '', 0, 0),
(13, 7, 0, 3, 1, 0, '', 0, 0),
(13, 7, 0, 4, 2, 0, '', 0, 0);

-- --------------------------------------------------------

--
-- Structure de la table `lang`
--

CREATE TABLE IF NOT EXISTS `lang` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  `shortname` varchar(8) NOT NULL,
  `enabled` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- Contenu de la table `lang`
--

INSERT INTO `lang` (`id`, `name`, `shortname`, `enabled`) VALUES
(2, 'Anglais', 'en', 0),
(1, 'Fran&ccedil;ais', 'fr', 1),
(4, 'Italien', 'it', 0),
(5, 'Espagnol', 'sp', 0),
(9, 'N&eacute;erlandais', 'nl', 0),
(10, 'Portugais', 'pt', 0),
(3, 'Allemand', 'de', 0),
(11, 'Russe', 'ru', 0),
(12, 'Chinois', 'cn', 0),
(13, 'Japonnais', 'jp', 0);

-- --------------------------------------------------------

--
-- Structure de la table `logs`
--

CREATE TABLE IF NOT EXISTS `logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `log_type` varchar(45) NOT NULL,
  `log_url` varchar(255) NOT NULL,
  `log_referer` varchar(255) NOT NULL,
  `log_info` varchar(255) NOT NULL,
  `id_user` int(10) unsigned NOT NULL,
  `log_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `menu`
--

CREATE TABLE IF NOT EXISTS `menu` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `menu_id` int(11) unsigned NOT NULL,
  `pri_type` tinyint(4) unsigned NOT NULL,
  `sec_type` varchar(20) NOT NULL,
  `parent` int(11) unsigned NOT NULL,
  `position` int(11) unsigned NOT NULL,
  `title` varchar(50) NOT NULL,
  `action` varchar(50) NOT NULL,
  `home` tinyint(1) unsigned NOT NULL,
  `enabled` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `parent` (`parent`),
  KEY `pri_type` (`pri_type`),
  KEY `enabled` (`enabled`),
  KEY `position` (`position`),
  KEY `home` (`home`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

--
-- Contenu de la table `menu`
--

INSERT INTO `menu` (`id`, `menu_id`, `pri_type`, `sec_type`, `parent`, `position`, `title`, `action`, `home`, `enabled`) VALUES
(1, 0, 1, 'horizontal', 0, 0, 'Principal', '#', 0, 1),
(2, 1, 2, 'url_self', 0, 0, 'Presentation', 'index.php?mod=sl_pages&id=1', 1, 1),
(3, 1, 2, 'url_self', 0, 1, 'Actualites', 'index.php?mod=sl_pages&id=2', 0, 1),
(5, 1, 2, 'url_self', 0, 2, 'Telechargement', 'index.php?mod=sl_pages&id=3', 0, 1),
(6, 1, 2, 'url_self', 0, 4, 'Contact', 'index.php?mod=sl_pages&id=4', 0, 1),
(15, 1, 2, 'url_self', 0, 3, 'Documentation', 'index.php?mod=sl_pages&id=5', 0, 1);

-- --------------------------------------------------------

--
-- Structure de la table `modules`
--

CREATE TABLE IF NOT EXISTS `modules` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(20) NOT NULL,
  `name` varchar(20) NOT NULL,
  `url` varchar(50) NOT NULL,
  `params` varchar(255) NOT NULL,
  `initialize_order` int(11) unsigned NOT NULL,
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=62 ;

--
-- Contenu de la table `modules`
--

INSERT INTO `modules` (`id`, `type`, `name`, `url`, `params`, `initialize_order`, `enabled`) VALUES
(1, 'site', 'sl_header', 'modules/sl_header/', '', 1, 1),
(2, 'site', 'sl_menu', 'modules/sl_menu/', '', 2, 1),
(3, 'admin', 'sla_secure', 'modules/sla_secure/', '', 1, 1),
(4, 'admin', 'sla_admmenu', 'modules/sla_admmenu/', '', 3, 1),
(5, 'admin', 'sla_header', 'modules/sla_header/', '', 2, 1),
(6, 'admin', 'sla_panel', 'modules/sla_panel/', '', 4, 1),
(7, 'admin', 'sla_categories', 'modules/sla_categories/', '', 0, 1),
(10, 'site', 'sl_error', 'modules/sl_error/', '', 3, 1),
(12, 'admin', 'sla_menu', 'modules/sla_menu/', '', 0, 1),
(13, 'admin', 'sla_articles', 'modules/sla_articles/', 'param1=test&param2=test2', 0, 1),
(14, 'admin', 'sla_users', 'modules/sla_users/', '', 0, 1),
(22, 'admin', 'sla_modules', 'modules/sla_modules/', '', 0, 1),
(27, 'admin', 'sla_news', 'modules/sla_news/', '', 0, 1),
(41, 'site', 'sl_pages', 'modules/sl_pages/', '', 0, 1),
(42, 'admin', 'sla_pages', 'modules/sla_pages/', '', 0, 1),
(44, 'admin', 'sla_country', 'modules/sla_country/', '', 0, 1),
(46, 'admin', 'sla_lang', 'modules/sla_lang/', '', 0, 1),
(56, 'site', 'sl_articles', 'modules/sl_articles/', '', 0, 1),
(59, 'admin', 'sla_config', 'modules/sla_config/', '', 0, 1),
(60, 'admin', 'sla_medias', 'modules/sla_medias/', '', 0, 1),
(60, 'admin', 'sla_logs', 'modules/sla_logs/', '', 0, 1);

-- --------------------------------------------------------

--
-- Structure de la table `news`
--

CREATE TABLE IF NOT EXISTS `news` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(11) unsigned NOT NULL,
  `datein` datetime NOT NULL,
  `dateout` datetime NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `date` datetime NOT NULL,
  `enabled` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `news`
--

INSERT INTO `news` (`id`, `id_user`, `datein`, `dateout`, `title`, `content`, `date`, `enabled`) VALUES
(1, 1, '2013-01-30 13:37:00', '0000-00-00 00:00:00', 'Mise en ligne du cms', '<p>\r\n J&#39;ai le plaisir de vous annoncer la mise en ligne</p>\r\n', '2013-01-29 13:18:21', 1),
(2, 1, '2013-02-01 10:10:00', '2013-02-22 20:00:00', 'Aenean nisl odio', '<p>\r\n ornare eu suscipit sed, congue vestibulum tortor. Aliquam sit amet velit odio. In porttitor bibendum nibh, eu convallis leo tincidunt nec. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. In vel leo dui, nec egestas lacus. Donec mi magna, fringilla vel ornare commodo, ultricies vel leo. Praesent tincidunt, orci non ornare ullamcorper, massa tortor tempor sem, sit amet bibendum libero erat eu eros.</p>\r\n', '2013-02-01 12:36:19', 1);

-- --------------------------------------------------------

--
-- Structure de la table `pages`
--

CREATE TABLE IF NOT EXISTS `pages` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(11) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `date` datetime NOT NULL,
  `enabled` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `enabled` (`enabled`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Contenu de la table `pages`
--

INSERT INTO `pages` (`id`, `id_user`, `title`, `content`, `date`, `enabled`) VALUES
(1, 1, 'Presentation', '<p>\r\n  Slash CMS, est une application web open source qui va vous permettre de cr&eacute;er<br />\r\n  et g&eacute;rer votre site internet tr&egrave;s simplement.<br />\r\n <br />\r\n  Avec ce syst&egrave;me de gestion de contenu sur internet, vous allez pouvoir mettre a disposition des visiteurs toutes les informations dont vous avez besoin de communiquer.<br />\r\n  <br />\r\n  Allant de la simple page &agrave; la galerie photo, en passant par des news flash et bien d&#39;autres modules,<br />\r\n vous pourrez afficher toutes vos donn&eacute;es sur votre site, sans avoir la moindre connaissance technique.</p>\r\n', '2013-01-29 13:08:34', 1),
(2, 1, 'Actualites', '<p>\r\n  A venir</p>\r\n', '2013-01-29 13:08:52', 1),
(3, 1, 'Telechargement', '<p>\r\n A venir.</p>\r\n', '2013-01-29 13:09:06', 1),
(4, 1, 'Contact', '<p>\r\n  Vous pouvez me contacter a cette adresse : weneedyou [arobase] slash-cms.com</p>\r\n', '2013-01-29 13:09:23', 1),
(5, 1, 'Documentation', '<p>\r\n  A venir.</p>\r\n', '2013-01-29 22:56:12', 1);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `login` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `mail` varchar(50) NOT NULL,
  `language` varchar(10) NOT NULL,
  `grade` int(11) unsigned NOT NULL,
  `allowed_module` varchar(255) NOT NULL,
  `enabled` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
