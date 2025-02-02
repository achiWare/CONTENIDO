DROP TABLE IF EXISTS `!PREFIX!_art_lang`;

CREATE TABLE `!PREFIX!_art_lang` (
  `idartlang` int(11) NOT NULL auto_increment,
  `idart` int(11) NOT NULL default '0',
  `idlang` int(11) NOT NULL default '0',
  `idtplcfg` int(11) NOT NULL default '0',
  `title` varchar(255) NOT NULL,
  `urlname` varchar(255) NOT NULL,
  `pagetitle` varchar(255) NOT NULL,
  `summary` text,
  `artspec` int(11) NOT NULL default '0',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `lastmodified` datetime NOT NULL default '0000-00-00 00:00:00',
  `author` varchar(32) default NULL,
  `modifiedby` varchar(32) default NULL,
  `published` datetime NOT NULL default '0000-00-00 00:00:00',
  `publishedby` varchar(32) default NULL,
  `online` tinyint(1) NOT NULL default '0',
  `redirect` int(6) NOT NULL default '0',
  `redirect_url` varchar(255) NOT NULL,
  `artsort` int(11) NOT NULL default '0',
  `timemgmt` tinyint(1) default NULL,
  `datestart` datetime default NULL,
  `dateend` datetime default NULL,
  `status` int(11) NOT NULL default '0',
  `free_use_01` mediumint(7) default NULL,
  `free_use_02` mediumint(7) default NULL,
  `free_use_03` mediumint(7) default NULL,
  `time_move_cat` mediumint(7) default NULL,
  `time_target_cat` mediumint(7) default NULL,
  `time_online_move` mediumint(7) default NULL,
  `external_redirect` char(1) NOT NULL,
  `locked` int(1) NOT NULL default '0',
  `searchable` tinyint(1) NOT NULL default '1',
  `sitemapprio` float NOT NULL default '0.5',
  `changefreq` varchar(12) NOT NULL,
  PRIMARY KEY (`idartlang`),
  KEY `idtplcfg` (`idtplcfg`,`idart`),
  KEY `idart_2` (`idart`,`idlang`)
) ENGINE=!ENGINE! DEFAULT CHARSET=!CHARSET! AUTO_INCREMENT=0;

INSERT INTO `!PREFIX!_art_lang` (`idartlang`, `idart`, `idlang`, `idtplcfg`, `title`, `urlname`, `pagetitle`, `summary`, `artspec`, `created`, `lastmodified`, `author`, `modifiedby`, `published`, `publishedby`, `online`, `redirect`, `redirect_url`, `artsort`, `timemgmt`, `datestart`, `dateend`, `status`, `free_use_01`, `free_use_02`, `free_use_03`, `time_move_cat`, `time_target_cat`, `time_online_move`, `external_redirect`, `locked`, `searchable`, `sitemapprio`, `changefreq`) VALUES
(1, 1, 1, 10, 'Startseite', 'index', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 1, 0.5, ''),
(20, 20, 1, 97, 'Suchergebnisse', 'Suchergebnisse', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 1, 0.5, ''),
(21, 21, 1, 0, 'Fehlerseite (404)', 'Fehlerseite', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 1, 0.5, ''),
(3, 3, 1, 19, 'RSS Creator', 'Rss-Creator', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 1, 0.5, ''),
(6, 6, 1, 0, 'Sitemap', 'Sitemap', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 0, 0, '0', 1, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 1, 0.5, ''),
(7, 7, 1, 0, 'Impressum', 'Impressum', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 2, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 1, 0.5, ''),
(8, 8, 1, 0, 'Datenschutz', 'Datenschutz', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 3, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 1, 0.5, ''),
(9, 9, 1, 0, 'FAQ', 'FAQ', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 1, 'https://faq.contenido.org/', 4, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 1, 0.5, ''),
(10, 10, 1, 0, 'Forum', 'Forum', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 1, 'https://forum.contenido.org', 5, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 1, 0.5, ''),
(16, 16, 1, 166, 'XML Sitemap', 'XML-Sitemap', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 1, 0.5, ''),
(17, 17, 1, 0, 'Template_Newsletter_HTML', 'Template_Newsletter_HTML', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 0, 0.5, ''),
(122, 82, 2, 0, 'Social Media', 'Social-Media', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 1, 0.5, ''),
(19, 19, 1, 53, 'Bildergalerie', 'Bildergalerie', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 1, 0.5, ''),
(12, 12, 1, 23, 'Footer Konfigurator', 'Footer-Konfigurator', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 1, 0.5, ''),
(13, 13, 1, 0, 'Philosophie', 'Philosophie', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 1, 0.5, ''),
(53, 52, 1, 0, 'Basissystem', 'Basissystem', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 1, 0.5, ''),
(15, 15, 1, 0, 'Fehlerseite', 'Fehlerseite', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 0, 0.5, ''),
(24, 24, 1, 0, 'Features dieser Website', 'features_website', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 1, 0.5, ''),
(79, 66, 2, 0, 'Just Publish', 'Just-Publish', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 1, 0.5, ''),
(29, 29, 1, 0, 'Navigation', 'navigation', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 1, 0.5, ''),
(31, 31, 1, 0, 'Content', 'content', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 1, 0.5, ''),
(32, 32, 1, 74, 'Teaser', 'Teaser', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 1, 0.5, ''),
(33, 33, 1, 0, 'Geschlossener Bereich', 'geschlossener_bereich', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', '2012-11-11 05:16:46', 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 1, 0.5, ''),
(54, 53, 1, 0, 'Inhaltspflege', 'Inhaltspflege', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 1, 0.5, ''),
(55, 54, 1, 0, 'Plugins', 'Plugins', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 1, 0.5, ''),
(56, 55, 1, 0, 'Dienstleistungen 4fb', 'Dienstleistungen-4fb', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 1, 0.5, ''),
(43, 43, 1, 0, 'Newsletter', 'newsletter', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 0, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 1, 0.5, ''),
(45, 45, 1, 0, 'HTML-Newsletter', 'newsletter', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 1, 0.5, ''),
(47, 46, 1, 0, 'Fakten und Funktionen', 'Fakten-und-funktionen', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 1, 0.5, ''),
(77, 76, 1, 94, 'Blog', 'Blog', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 1, 0.5, ''),
(75, 74, 1, 0, 'Blog Testartikel 2', 'Blog-Testartikel-2', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin',NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 1, 0.5, ''),
(76, 75, 1, 0, 'Blog Testartikel 3', 'Blog-Testartikel-3', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 1, 0.5, ''),
(51, 50, 1, 96, 'Kontakt', 'Kontakt', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 1, 0.5, ''),
(52, 51, 1, 0, 'Startartikel', 'Startartikel', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 1, 0.5, ''),
(57, 56, 1, 70, 'Sitemap', 'Sitemap', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 1, 0.5, ''),
(59, 58, 1, 73, 'Linkliste', 'Linkliste', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 1, 0.5, ''),
(60, 59, 1, 0, 'Artikel 1', 'Artikel-1', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 1, 0.5, ''),
(61, 60, 1, 0, 'Artikel 2', 'Artikel-2', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 1, 0.5, ''),
(62, 61, 1, 78, 'Downloadliste', 'Downloadliste', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 1, 0.5, ''),
(64, 63, 1, 0, 'Artikel 3', 'Artikel-3', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 1, 0.5, ''),
(65, 64, 1, 0, 'Implementierung', 'Implementierung', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 1, 0.5, ''),
(66, 65, 1, 0, 'Upgrade', 'Upgrade', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 1, 0.5, ''),
(67, 66, 1, 0, 'Einfach benutzen', 'Einfach-benutzen', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 1, 0.5, ''),
(68, 67, 1, 0, 'Einfach grenzenlos', 'Einfach-grenzenlos', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 1, 0.5, ''),
(69, 68, 1, 0, 'Einfach einfach', 'Einfach-einfach', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 1, 0.5, ''),
(70, 69, 1, 0, 'Blog Testartikel 1', 'Blog-Testartikel-1', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 1, 0.5, ''),
(71, 70, 1, 0, 'Newsletter', 'Newsletter', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 1, 0.5, ''),
(78, 13, 2, 0, 'Philosophy', 'Philosophy', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 1, 0.5, ''),
(80, 67, 2, 0, 'Just Unlimited', 'Just-Unlimited', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 1, 0.5, ''),
(81, 68, 2, 0, 'Just simple', 'just-simple', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 1, 0.5, ''),
(82, 46, 2, 0, 'Facts and Functions', 'Facts-and-Functions', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 1, 0.5, ''),
(83, 53, 2, 0, 'Content', 'content', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 1, 0.5, ''),
(84, 52, 2, 0, 'Basis System', 'Basis-System', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 1, 0.5, ''),
(85, 54, 2, 0, 'Plugins', 'Plugins', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, '', 0, 1, 0.5, ''),
(86, 24, 2, 0, 'Features dieser Website', 'features_website', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, '', 0, 1, 0.5, ''),
(87, 29, 2, 0, 'Navigation', 'navigation', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, '', 0, 1, 0.5, ''),
(88, 31, 2, 0, 'Content', 'content', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, '', 0, 1, 0.5, ''),
(89, 50, 2, 96, 'Startartikel', 'Startartikel', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, '', 0, 1, 0.5, ''),
(90, 56, 2, 70, 'Sitemap', 'Sitemap', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, '', 0, 1, 0.5, ''),
(91, 51, 2, 0, 'Startartikel', 'Startartikel', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, '', 0, 1, 0.5, ''),
(92, 8, 2, 0, 'Privacy', 'Privacy', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 3, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 1, 0.5, ''),
(93, 9, 2, 0, 'FAQ', 'FAQ', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 1, 'http://faq.contenido.org/', 4, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, '', 0, 1, 0.5, ''),
(94, 10, 2, 0, 'Forum', 'Forum', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 1, 'http://forum.contenido.org', 5, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, '', 0, 1, 0.5, ''),
(95, 7, 2, 0, 'Imprint', 'Imprint', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 2, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 1, 0.5, ''),
(96, 43, 2, 0, 'Newsletter', 'newsletter', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), NULL, 0, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, '', 0, 1, 0.5, ''),
(97, 15, 2, 0, 'Fehlerseite', 'Fehlerseite', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, '', 0, 0, 0.5, ''),
(98, 12, 2, 23, 'Footer Konfigurator', 'Footer-Konfigurator', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, '', 0, 1, 0.5, ''),
(99, 3, 2, 19, 'RSS Creator', 'Rss-Creator', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, '', 0, 1, 0.5, ''),
(100, 21, 2, 0, 'Error page (404)', 'Error-page', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 1, 0.5, ''),
(101, 20, 2, 97, 'Search results', 'Search-results', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 1, 0.5, ''),
(123, 83, 2, 149, 'Facebook', 'Facebook', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 1, 0.5, ''),
(103, 16, 2, 0, 'XML Sitemap', 'XML-Sitemap', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, '', 0, 1, 0.5, ''),
(105, 59, 2, 0, 'Article 1', 'Article-1', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 1, 0.5, ''),
(106, 60, 2, 0, 'Article 2', 'Article-2', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 1, 0.5, ''),
(107, 63, 2, 0, 'Article 3', 'Article-3', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 1, 0.5, ''),
(108, 32, 2, 74, 'Teaser', 'Teaser', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, '', 0, 1, 0.5, ''),
(109, 19, 2, 53, 'Picture Gallery', 'Picture-Gallery', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 1, 0.5, ''),
(110, 33, 2, 0, 'Protected Area', 'Protected-Area', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 1, 0.5, ''),
(111, 58, 2, 73, 'link list', 'link-list', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 1, 0.5, ''),
(112, 61, 2, 78, 'Download List', 'Download-List', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 1, 0.5, ''),
(113, 76, 2, 94, 'Blog', 'Blog', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, '', 0, 1, 0.5, ''),
(114, 69, 2, 0, 'Blog Testarticle', 'Blog-Testarticle', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 1, 0.5, ''),
(115, 74, 2, 0, 'Blog Testarticle3', 'Blog-Testarticle3', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 1, 0.5, ''),
(116, 75, 2, 0, 'Blog Testarticle2', 'Blog-Testarticle2', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 1, 0.5, ''),
(117, 77, 1, 0, 'Start', 'Start', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 1, 0.5, ''),
(118, 77, 2, 0, 'Start', 'Start', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, '', 0, 1, 0.5, ''),
(119, 78, 1, 0, 'Fehlerhafter Login', 'Fehlerhafter-Login', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 0, 0.5, ''),
(120, 78, 2, 0, 'Fehlerhafter Login', 'Fehlerhafter-Login', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, '', 0, 0, 0.5, ''),
(124, 84, 2, 151, 'XING', 'XING', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 1, 0.5, ''),
(125, 85, 2, 153, 'Google Plus', 'Google-Plus', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 1, 0.5, ''),
(126, 86, 2, 155, 'Twitter', 'Twitter', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 1, 0.5, ''),
(127, 82, 1, 0, 'Social Media', 'Social-Media', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 1, 0.5, ''),
(128, 83, 1, 149, 'Facebook', 'Facebook', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 1, 0.5, ''),
(129, 86, 1, 155, 'Twitter', 'Twitter', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 1, 0.5, ''),
(130, 84, 1, 151, 'XING', 'XING', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 1, 0.5, ''),
(131, 85, 1, 153, 'Google Plus', 'Google-Plus', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 1, 0.5, ''),
(132, 87, 1, 0, 'Facebook Channel', 'Facebook-Channel', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, '', 0, 1, 0.5, ''),
(133, 87, 2, 0, 'Facebook Channel', 'Facebook-Channel', '', NULL, 0, NOW(), NOW(), 'sysadmin', 'sysadmin', NOW(), 'sysadmin', 1, 0, '0', 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, '', 0, 1, 0.5, '');