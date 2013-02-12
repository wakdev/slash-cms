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
-- Structure de la table `sl_admmenu`
--

CREATE TABLE IF NOT EXISTS `sl_admmenu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` text NOT NULL,
  `parent` int(11) NOT NULL,
  `position` int(11) NOT NULL,
  `title_fr` text NOT NULL,
  `title_en` text NOT NULL,
  `icon` text NOT NULL,
  `action` text NOT NULL,
  `enabled` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=47 ;

--
-- Contenu de la table `sl_admmenu`
--

INSERT INTO `sl_admmenu` (`id`, `type`, `parent`, `position`, `title_fr`, `title_en`, `icon`, `action`, `enabled`) VALUES
(1, 'none', 0, 1, 'Site', 'Website', 'site.png', '#', 1),
(2, 'none', 0, 3, 'Configuration', 'Config', 'config.png', '#', 1),
(3, 'url_self', 1, 3, 'Gestion des articles', 'Articles', 'articles.png', 'index.php?mod=sla_articles', 1),
(6, 'url_self', 1, 1, 'Tableau de bord', 'Main panel', 'panel.png', 'index.php?mod=sla_panel', 1),
(10, 'none', 0, 2, 'Modules', 'Modules', 'modules.png', '#', 1),
(11, 'url_self', 1, 4, 'Gestion des news', 'News', 'news.png', 'index.php?mod=sla_news', 1),
(12, 'url_self', 2, 1, 'Gestion des th&egrave;mes', 'Templates config', 'template.png', 'index.php?mod=sla_template', 0),
(14, 'none', 0, 4, 'Aide', 'Help', 'help.png', '#', 1),
(15, 'url_self', 14, 1, 'Support technique', 'Technical support', 'support.png', '', 0),
(16, 'url_self', 1, 5, 'Gestion des menus', 'Menus config', 'menus.png', 'index.php?mod=sla_menu', 1),
(17, 'url_self', 2, 2, 'Gestion utilisateurs', 'Users settings', 'users.png', 'index.php?mod=sla_users', 1),
(19, 'url_self', 1, 2, 'Gestion des cat&eacute;gories', 'Categories', 'categories.png', 'index.php?mod=sla_categories', 1),
(23, 'url_self', 2, 3, 'Gestion des modules BETA', 'Modules configuration', 'config2.png', 'index.php?mod=sla_modules', 1),
(36, 'url_self', 10, 2, 'Gestion des pages', 'Pages', 'page.png', 'index.php?mod=sla_pages', 1),
(38, 'url_self', 2, 4, 'Gestion des Pays', 'Country management', 'country.png', 'index.php?mod=sla_country', 1),
(39, 'url_self', 2, 5, 'Gestion des Langues', 'Languages management', 'lang.png', 'index.php?mod=sla_lang', 1);

-- --------------------------------------------------------

--
-- Structure de la table `sl_articles`
--

CREATE TABLE IF NOT EXISTS `sl_articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `categories` varchar(255) NOT NULL,
  `title` text NOT NULL,
  `content` text NOT NULL,
  `date` datetime NOT NULL,
  `enabled` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- Contenu de la table `sl_articles`
--

INSERT INTO `sl_articles` (`id`, `id_user`, `categories`, `title`, `content`, `date`, `enabled`) VALUES
(1, 1, '2', 'L''Ã©quipe', '<p>\r\n	Du contenu...</p>\r\n', '2013-01-29 13:17:43', 1),
(2, 1, '', 'Lorem ipsum', '<p>\r\n	Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse non felis et erat interdum lobortis et non quam. Nulla lectus magna, scelerisque eu facilisis vitae, tristique at metus. Cras lacinia consequat sapien, rutrum lacinia nunc sodales sed. Nullam pellentesque dictum dolor, nec varius orci gravida sit amet. Aenean vulputate, est eget porttitor congue, augue quam dapibus augue, et venenatis mauris arcu sed nunc. Praesent tristique porta augue a tempor. Nullam rhoncus justo in tellus condimentum tincidunt. Fusce nec quam mi. Nunc eu nibh orci, eget sagittis felis. Aenean cursus luctus est, non feugiat quam feugiat vel.</p>\r\n', '2013-02-01 12:31:01', 0),
(3, 1, '2,1', 'Aliquam erat volutpat.', '<p>\r\n	Nunc ante augue, imperdiet a euismod at, pharetra et diam. Phasellus tempus urna vitae mauris congue at egestas nibh rutrum. Vestibulum in diam eget neque tincidunt adipiscing. Sed vel facilisis lectus. Phasellus vulputate arcu eget neque pharetra lobortis blandit ligula dapibus. Mauris auctor feugiat vehicula. Sed pulvinar convallis velit id rutrum. Cras magna metus, vehicula at ultrices non, semper euismod magna. Mauris mollis mollis interdum. Proin mattis neque et magna rhoncus posuere. Aenean nec ipsum non ipsum consectetur mollis a ac dolor.</p>\r\n', '2013-02-01 12:31:24', 1),
(4, 1, '', 'Aliquam nec', '<p>\r\n	ulla laoreet nisl pellentesque commodo. Maecenas vitae velit tellus, et sagittis nulla. Nulla sollicitudin molestie posuere. Quisque in ligula augue, ut accumsan eros. Praesent nisl metus, aliquam non porttitor sed, euismod in lacus. Suspendisse vel arcu et lorem aliquet egestas. Phasellus vitae ante nec augue rhoncus lacinia. Donec pulvinar accumsan justo, eget volutpat velit elementum at. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Etiam eleifend velit vel risus iaculis tempor. Morbi vel elit orci, sed vulputate elit.</p>\r\n', '2013-02-01 12:31:49', 1),
(5, 1, '1', 'Quisque nisl ligula', '<p>\r\n	congue vel consequat in, dignissim non nibh. Aenean sagittis massa et orci molestie in pellentesque lorem fringilla. Phasellus scelerisque suscipit ligula, et mattis dui semper nec. Nam enim velit, venenatis vel fermentum gravida, varius quis ligula. Morbi semper massa tincidunt erat tincidunt egestas. Sed eu nibh tellus, nec sagittis mi. Suspendisse pharetra elementum vulputate. Integer euismod hendrerit eleifend. Sed lorem turpis, semper vel pharetra ut, cursus sit amet ipsum.</p>\r\n', '2013-02-01 12:32:11', 0),
(6, 1, '2,1', 'Praesent lacus nulla', '<p>\r\n	faucibus nec placerat id, hendrerit et turpis. Nam ornare gravida lacus, ut interdum tellus posuere a. Pellentesque in nisl non risus tincidunt tempor non eget tortor. In arcu ipsum, sollicitudin vel convallis id, aliquet vitae augue. In ut eleifend quam. Quisque vitae dui dui, id molestie nulla. Phasellus aliquet scelerisque dui id tempor. Vestibulum hendrerit nibh ac orci mattis tempor. Pellentesque quis urna quis dolor tincidunt egestas. Duis at nibh libero, sit amet euismod eros.</p>\r\n', '2013-02-01 12:32:28', 0),
(7, 1, '2,1', 'Maecenas id eros', '<p>\r\n	ac tellus congue elementum. Praesent euismod, urna id lobortis volutpat, turpis leo rutrum elit, a interdum lectus justo quis dolor. Nulla facilisi. Nam ut blandit leo. Sed hendrerit, nisi ac lobortis dignissim, massa urna imperdiet justo, ut luctus urna justo vitae leo. Morbi tincidunt neque sed arcu fermentum porttitor. Ut pharetra consequat lorem vel pharetra. Vivamus non nibh a tellus iaculis mattis. Aenean ligula nulla, convallis vitae consectetur eleifend, luctus tempus velit. Fusce id augue massa, vel convallis nunc. Cras non nisi dui. Integer egestas dignissim volutpat. Cras scelerisque lorem ut eros consequat congue. Nullam sit amet nisl mattis velit vestibulum euismod. Vivamus vulputate, velit nec feugiat hendrerit, metus lectus tempor nulla, non adipiscing diam elit sed massa.</p>\r\n', '2013-02-01 12:32:57', 1),
(8, 1, '2,1', 'Phasellus hendrerit', '<p>\r\n	ligula at placerat interdum, neque nulla dictum mauris, vitae aliquet nisi orci sed tortor. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Mauris in congue nulla. Aliquam vel nisl massa, at faucibus nisl. In ac sem vehicula felis euismod aliquam. Vivamus eu lorem lorem. Nulla ullamcorper condimentum arcu nec pulvinar. Vivamus vitae arcu neque. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Quisque in neque a leo lobortis ultrices. Vivamus sagittis varius dui sit amet tincidunt. Phasellus nec risus eget erat aliquam semper eget non est. Phasellus vestibulum ante eu elit sagittis elementum quis eu ipsum. Nulla facilisi. Suspendisse vel metus justo, eu elementum eros.</p>\r\n', '2013-02-01 12:33:18', 0),
(9, 1, '2,1', 'Quisque sit amet', '<p>\r\n	sit amet pellentesque purus. Morbi posuere vestibulum lacinia. Suspendisse potenti. Maecenas dictum, est eu faucibus malesuada, sem libero ultricies neque, a congue tellus lectus sit amet massa. Curabitur in eleifend ante. Donec sed lectus enim, quis sagittis eros. Ut blandit rhoncus elementum. Aenean nec tellus est, in auctor ante. Cras vel est nulla. Etiam massa arcu, pellentesque et venenatis ac, convallis sed risus.</p>\r\n', '2013-02-01 12:33:37', 0),
(10, 1, '', 'Curabitur eleifend', '<p>\r\n	Curabitur eleifend volutpat quam in luctus. In pretium, tellus a malesuada posuere, mauris arcu egestas erat, a tincidunt dolor lectus et dui. Donec neque felis, suscipit a blandit vitae, malesuada vitae dui. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Sed consequat, risus at aliquet malesuada, augue metus condimentum arcu, ut gravida felis sem at erat. Ut eleifend odio ac libero tincidunt ornare. Cras ultricies venenatis purus, at scelerisque mi aliquam sed. Sed urna felis, faucibus et consequat interdum, imperdiet in diam. Vivamus vitae ornare lorem. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nullam accumsan tempor velit, non sollicitudin nunc mattis ac. Etiam eu ipsum ligula.</p>\r\n', '2013-02-01 12:33:55', 0),
(11, 1, '', 'Proin at dictum sem', '<p>\r\n	Vestibulum facilisis molestie feugiat. Donec tempor tempor nunc quis fermentum. Fusce congue est ut lorem adipiscing ac hendrerit nunc suscipit. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam vulputate diam sed turpis facilisis eu suscipit nisi elementum. Nam pellentesque condimentum nulla, id aliquam arcu rutrum tempor. Donec rutrum iaculis urna, id mattis tellus dictum vehicula. Phasellus a tempor orci. Nam venenatis sapien id magna consequat non ullamcorper nisi dignissim. Sed at tortor nisi. Morbi porttitor, lectus sed consectetur feugiat, turpis lectus tristique urna, id malesuada quam mi ut magna. Vestibulum sit amet elit est. Sed ut libero lorem. Praesent ut enim purus. Donec nisl nisi, vehicula nec tempus quis, accumsan non mauris.</p>\r\n', '2013-02-01 12:34:15', 0),
(12, 1, '2', 'Fusce a arcu nisi, eget dapibus augue.', '<p>\r\n	Pellentesque eget venenatis turpis. Mauris porttitor mauris ac tortor congue non mattis ante pharetra. Maecenas nec mi sem. Etiam tempus, diam a facilisis sollicitudin, velit mauris adipiscing mauris, vel molestie ligula lectus sit amet leo. Nullam condimentum leo non ipsum laoreet quis tempus nibh fermentum. Cras porttitor pellentesque rhoncus. Sed viverra posuere mollis. Mauris augue leo, euismod a vestibulum quis, pellentesque id ante.</p>\r\n', '2013-02-01 12:34:38', 0),
(13, 1, '2,1', 'Praesent nisl tortor', '<p>\r\n	a feugiat pulvinar, feugiat eget velit. Pellentesque nec massa felis, ut aliquet ipsum. Duis molestie gravida massa sed tristique. Maecenas porttitor vehicula ipsum at feugiat. Sed sit amet ipsum tortor. Suspendisse id erat non magna vestibulum rutrum sed a orci. Etiam lacus enim, consequat sit amet vehicula eu, euismod id tortor. Quisque dignissim, ante eget euismod laoreet, sapien purus faucibus magna, pulvinar varius lorem quam eget ligula. Donec suscipit, libero vitae posuere dignissim, nulla ipsum sollicitudin nulla, id tincidunt ligula nisi eget lectus. Nulla pulvinar adipiscing dolor sed scelerisque. Vestibulum luctus fringilla diam at dapibus.</p>\r\n', '2013-02-01 12:34:58', 0);

-- --------------------------------------------------------

--
-- Structure de la table `sl_attachments`
--

CREATE TABLE IF NOT EXISTS `sl_attachments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `id_module` int(11) NOT NULL,
  `id_element` int(11) NOT NULL,
  `id_field` varchar(11) NOT NULL,
  `filename` text NOT NULL,
  `position` int(11) NOT NULL,
  `state` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `sl_categories`
--

CREATE TABLE IF NOT EXISTS `sl_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `title` text NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `sl_categories`
--

INSERT INTO `sl_categories` (`id`, `id_user`, `title`, `description`) VALUES
(1, 1, 'Wiki', '<p>\r\n	Articles du wiki</p>\r\n'),
(2, 1, 'Projet', '<p>\r\n	Articles du projet</p>\r\n');

-- --------------------------------------------------------

--
-- Structure de la table `sl_config`
--

CREATE TABLE IF NOT EXISTS `sl_config` (
  `config_name` text NOT NULL,
  `config_value` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Contenu de la table `sl_config`
--

INSERT INTO `sl_config` (`config_name`, `config_value`) VALUES
('site_name', 'slash cms'),
('global_description', 'slash : le cms intuitif'),
('global_keywords', 'cms, slash, slash-cms, siteweb'),
('seo_enabled', 'true'),
('slash_language', 'fr'),
('site_template_url', 'templates/slashcms/'),
('admin_template_url', 'templates/wd-admin/'),
('admin_email', 'julien@wakdev.com'),
('mobile_template_url', 'templates/default_mobile/'),
('mobile_detection', 'true');

-- --------------------------------------------------------

--
-- Structure de la table `sl_country`
--

CREATE TABLE IF NOT EXISTS `sl_country` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) CHARACTER SET latin1 NOT NULL,
  `shortname` varchar(8) CHARACTER SET latin1 NOT NULL,
  `enabled` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Contenu de la table `sl_country`
--

INSERT INTO `sl_country` (`id`, `name`, `shortname`, `enabled`) VALUES
(1, 'France', 'Fr', 1);

-- --------------------------------------------------------

--
-- Structure de la table `sl_lang`
--

CREATE TABLE IF NOT EXISTS `sl_lang` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) CHARACTER SET latin1 NOT NULL,
  `shortname` varchar(8) CHARACTER SET latin1 NOT NULL,
  `enabled` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=23 ;

--
-- Contenu de la table `sl_lang`
--

INSERT INTO `sl_lang` (`id`, `name`, `shortname`, `enabled`) VALUES
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
-- Structure de la table `sl_menu`
--

CREATE TABLE IF NOT EXISTS `sl_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_id` int(11) NOT NULL,
  `pri_type` int(11) NOT NULL,
  `sec_type` text NOT NULL,
  `parent` int(11) NOT NULL,
  `position` int(11) NOT NULL,
  `title` text NOT NULL,
  `action` text NOT NULL,
  `home` int(11) NOT NULL,
  `enabled` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

--
-- Contenu de la table `sl_menu`
--

INSERT INTO `sl_menu` (`id`, `menu_id`, `pri_type`, `sec_type`, `parent`, `position`, `title`, `action`, `home`, `enabled`) VALUES
(1, 0, 1, 'horizontal', 0, 0, 'Principal', '#', 0, 1),
(2, 1, 2, 'url_self', 0, 0, 'PrÃ©sentation', 'index.php?mod=sl_pages&id=1', 1, 1),
(3, 1, 2, 'url_self', 0, 1, 'ActualitÃ©s', 'index.php?mod=sl_pages&id=2', 0, 1),
(5, 1, 2, 'url_self', 0, 2, 'TÃ©lÃ©chargement', 'index.php?mod=sl_pages&id=3', 0, 1),
(6, 1, 2, 'url_self', 0, 4, 'Contact', 'index.php?mod=sl_pages&id=4', 0, 1),
(15, 1, 2, 'url_self', 0, 3, 'Documentation', 'index.php?mod=sl_pages&id=5', 0, 1);

-- --------------------------------------------------------

--
-- Structure de la table `sl_modules`
--

CREATE TABLE IF NOT EXISTS `sl_modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` text NOT NULL,
  `name` text NOT NULL,
  `url` text NOT NULL,
  `params` text NOT NULL,
  `initialize_order` int(11) NOT NULL,
  `enabled` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=59 ;

--
-- Contenu de la table `sl_modules`
--

INSERT INTO `sl_modules` (`id`, `type`, `name`, `url`, `params`, `initialize_order`, `enabled`) VALUES
(1, 'site', 'sl_header', 'modules/sl_header/', '', 1, 1),
(2, 'site', 'sl_menu', 'modules/sl_menu/', '', 2, 1),
(3, 'admin', 'sla_secure', 'modules/sla_secure/', '', 1, 1),
(4, 'admin', 'sla_admmenu', 'modules/sla_admmenu/', '', 3, 1),
(5, 'admin', 'sla_header', 'modules/sla_header/', '', 2, 1),
(6, 'admin', 'sla_panel', 'modules/sla_panel/', '', 4, 1),
(7, 'admin', 'sla_categories', 'modules/sla_categories/', '', 0, 1),
(10, 'site', 'sl_error', 'modules/sl_error/', '', 3, 1),
(12, 'admin', 'sla_menu', 'modules/sla_menu/', '', 0, 1),
(13, 'admin', 'sla_articles', 'modules/sla_articles/', '', 0, 1),
(14, 'admin', 'sla_users', 'modules/sla_users/', '', 0, 1),
(22, 'admin', 'sla_modules', 'modules/sla_modules/', '', 0, 1),
(27, 'admin', 'sla_news', 'modules/sla_news/', '', 0, 1),
(41, 'site', 'sl_pages', 'modules/sl_pages/', '', 0, 1),
(42, 'admin', 'sla_pages', 'modules/sla_pages/', '', 0, 1),
(44, 'admin', 'sla_country', 'modules/sla_country/', '', 0, 1),
(46, 'admin', 'sla_lang', 'modules/sla_lang/', '', 0, 1),
(56, 'site', 'sl_articles', 'modules/sl_articles/', '', 0, 1);

-- --------------------------------------------------------

--
-- Structure de la table `sl_news`
--

CREATE TABLE IF NOT EXISTS `sl_news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `datein` datetime NOT NULL,
  `dateout` datetime NOT NULL,
  `title` text NOT NULL,
  `content` text NOT NULL,
  `date` datetime NOT NULL,
  `enabled` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `sl_news`
--

INSERT INTO `sl_news` (`id`, `id_user`, `datein`, `dateout`, `title`, `content`, `date`, `enabled`) VALUES
(1, 1, '2013-01-30 13:37:00', '0000-00-00 00:00:00', 'Mise en ligne du cms', '<p>\r\n	J&#39;ai le plaisir de vous annoncer la mise en ligne</p>\r\n', '2013-01-29 13:18:21', 1),
(2, 1, '2013-02-01 10:10:00', '2013-02-22 20:00:00', 'Aenean nisl odio', '<p>\r\n	ornare eu suscipit sed, congue vestibulum tortor. Aliquam sit amet velit odio. In porttitor bibendum nibh, eu convallis leo tincidunt nec. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. In vel leo dui, nec egestas lacus. Donec mi magna, fringilla vel ornare commodo, ultricies vel leo. Praesent tincidunt, orci non ornare ullamcorper, massa tortor tempor sem, sit amet bibendum libero erat eu eros.</p>\r\n', '2013-02-01 12:36:19', 1);

-- --------------------------------------------------------

--
-- Structure de la table `sl_pages`
--

CREATE TABLE IF NOT EXISTS `sl_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `title` text NOT NULL,
  `content` text NOT NULL,
  `date` datetime NOT NULL,
  `enabled` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Contenu de la table `sl_pages`
--

INSERT INTO `sl_pages` (`id`, `id_user`, `title`, `content`, `date`, `enabled`) VALUES
(1, 1, 'PrÃ©sentation', '<p>\r\n	Slash CMS, est une application web open source qui va vous permettre de cr&eacute;er<br />\r\n	et g&eacute;rer votre site internet tr&egrave;s simplement.<br />\r\n	<br />\r\n	Avec ce syst&egrave;me de gestion de contenu sur internet, vous allez pouvoir mettre a disposition des visiteurs toutes les informations dont vous avez besoin de communiquer.<br />\r\n	<br />\r\n	Allant de la simple page &agrave; la galerie photo, en passant par des news flash et bien d&#39;autres modules,<br />\r\n	vous pourrez afficher toutes vos donn&eacute;es sur votre site, sans avoir la moindre connaissance technique.</p>\r\n', '2013-01-29 13:08:34', 1),
(2, 1, 'ActualitÃ©s', '<p>\r\n	A venir.</p>\r\n', '2013-01-29 13:08:52', 1),
(3, 1, 'TÃ©lÃ©chargement', '<p>\r\n	A venir.</p>\r\n', '2013-01-29 13:09:06', 1),
(4, 1, 'Contact', '<p>\r\n	Vous pouvez me contacter a cette adresse : weneedyou [arobase] slash-cms.com</p>\r\n', '2013-01-29 13:09:23', 1),
(5, 1, 'Documentation', '<p>\r\n	A venir.</p>\r\n', '2013-01-29 22:56:12', 1);

-- --------------------------------------------------------

--
-- Structure de la table `sl_users`
--

CREATE TABLE IF NOT EXISTS `sl_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `login` text NOT NULL,
  `password` text NOT NULL,
  `mail` text NOT NULL,
  `language` text NOT NULL,
  `grade` int(11) NOT NULL,
  `allowed_module` text NOT NULL,
  `enabled` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

--
-- Contenu de la table `sl_users`
--

INSERT INTO `sl_users` (`id`, `name`, `login`, `password`, `mail`, `language`, `grade`, `allowed_module`, `enabled`) VALUES
(1, 'John Doe', 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 'weneedyou@slash-cms.com', 'fr', 0, '', 1),
(17, 'John Doe', 'admin-en', 'd033e22ae348aeb5660fc2140aec35850c4da997', 'julien@wakdev.com', 'en', 0, '', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
