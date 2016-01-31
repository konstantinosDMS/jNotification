DROP TABLE IF EXISTS `#__notification_header`;
DROP TABLE IF EXISTS `#__notification_user`;
DROP TABLE IF EXISTS `#__notification_domains`;

CREATE TABLE `#__notification_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `domain` varchar(100) NOT NULL,
  `message_id` int(2) NOT NULL,
  `inbox` int(2) NOT NULL,
  `outbox` int(2) NOT NULL,
  `status` int(2) NOT NULL,
  `success` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `#__notification_header` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message` text NOT NULL,
  `title` varchar(45) NOT NULL,
  `created` datetime NOT NULL,
  `status` int(2) DEFAULT '0',
  PRIMARY KEY (`id`),
  FULLTEXT KEY `Haystack` (`message`,`title`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `#__notification_domains` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `domains` varchar(100) NOT NULL,
  `title` varchar(45) NOT NULL,
  `siteName` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;