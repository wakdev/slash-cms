--
-- Contenu de la table `categories`
--

INSERT INTO `categories` (`id`, `id_user`, `title`, `description`) VALUES
(1, 1, 'Wiki', '<p>\r\n Articles du wiki</p>\r\n'),
(2, 1, 'Projet', '<p>\r\n Articles du projet</p>\r\n');


--
-- Contenu de la table `joins`
--

INSERT INTO `joins` (`id_mod1`, `id_mod2`, `id_mod3`, `id1`, `id2`, `id3`, `item_data`, `item_order`, `item_enabled`) VALUES
(13, 7, 0, 3, 2, 0, '', 0, 0),
(13, 7, 0, 3, 1, 0, '', 0, 0),
(13, 7, 0, 4, 2, 0, '', 0, 0);

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

--
-- Contenu de la table `news`
--

INSERT INTO `news` (`id`, `id_user`, `datein`, `dateout`, `title`, `content`, `date`, `enabled`) VALUES
(1, 1, '2013-01-30 13:37:00', '0000-00-00 00:00:00', 'Mise en ligne du cms', '<p>\r\n J&#39;ai le plaisir de vous annoncer la mise en ligne</p>\r\n', '2013-01-29 13:18:21', 1),
(2, 1, '2013-02-01 10:10:00', '2013-02-22 20:00:00', 'Aenean nisl odio', '<p>\r\n ornare eu suscipit sed, congue vestibulum tortor. Aliquam sit amet velit odio. In porttitor bibendum nibh, eu convallis leo tincidunt nec. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. In vel leo dui, nec egestas lacus. Donec mi magna, fringilla vel ornare commodo, ultricies vel leo. Praesent tincidunt, orci non ornare ullamcorper, massa tortor tempor sem, sit amet bibendum libero erat eu eros.</p>\r\n', '2013-02-01 12:36:19', 1);

--
-- Contenu de la table `pages`
--

INSERT INTO `pages` (`id`, `id_user`, `title`, `content`, `date`, `responsive_images`,`enabled`) VALUES
(1, 1, 'Presentation', '<p>\r\n  Slash CMS, est une application web open source qui va vous permettre de cr&eacute;er<br />\r\n  et g&eacute;rer votre site internet tr&egrave;s simplement.<br />\r\n <br />\r\n  Avec ce syst&egrave;me de gestion de contenu sur internet, vous allez pouvoir mettre a disposition des visiteurs toutes les informations dont vous avez besoin de communiquer.<br />\r\n  <br />\r\n  Allant de la simple page &agrave; la galerie photo, en passant par des news flash et bien d&#39;autres modules,<br />\r\n vous pourrez afficher toutes vos donn&eacute;es sur votre site, sans avoir la moindre connaissance technique.</p>\r\n', '2013-01-29 13:08:34', 1, 1),
(2, 1, 'Actualites', '<p>\r\n  A venir</p>\r\n', '2013-01-29 13:08:52', 1, 1),
(3, 1, 'Telechargement', '<p>\r\n A venir.</p>\r\n', '2013-01-29 13:09:06', 1, 1),
(4, 1, 'Contact', '<p>\r\n  Vous pouvez me contacter a cette adresse : weneedyou [arobase] slash-cms.com</p>\r\n', '2013-01-29 13:09:23', 1, 1),
(5, 1, 'Documentation', '<p>\r\n  A venir.</p>\r\n', '2013-01-29 22:56:12', 1, 1);