# Sequel Pro dump
# Version 2492
# http://code.google.com/p/sequel-pro
#
# Host: localhost (MySQL 5.5.9)
# Database: pets
# Generation Time: 2012-05-28 06:42:12 +0000
# ************************************************************

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table friends
# ------------------------------------------------------------

DROP TABLE IF EXISTS `friends`;

CREATE TABLE `friends` (
  `me` int(11) DEFAULT NULL,
  `friend` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `friends` WRITE;
/*!40000 ALTER TABLE `friends` DISABLE KEYS */;
INSERT INTO `friends` (`me`,`friend`)
VALUES
	(3,2),
	(3,1);

/*!40000 ALTER TABLE `friends` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table roles
# ------------------------------------------------------------

DROP TABLE IF EXISTS `roles`;

CREATE TABLE `roles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` (`id`,`name`,`description`)
VALUES
	(1,'login','Login privileges, granted after account confirmation'),
	(2,'admin','Administrative user, has access to everything.');

/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table roles_users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `roles_users`;

CREATE TABLE `roles_users` (
  `user_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `fk_role_id` (`role_id`),
  CONSTRAINT `roles_users_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `roles_users_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `roles_users` WRITE;
/*!40000 ALTER TABLE `roles_users` DISABLE KEYS */;
INSERT INTO `roles_users` (`user_id`,`role_id`)
VALUES
	(1,1),
	(7,1),
	(8,1);

/*!40000 ALTER TABLE `roles_users` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table sessions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `sessions`;

CREATE TABLE `sessions` (
  `session_id` varchar(32) NOT NULL,
  `last_active` int(10) unsigned NOT NULL,
  `contents` text NOT NULL,
  PRIMARY KEY (`session_id`),
  KEY `last_active` (`last_active`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` (`session_id`,`last_active`,`contents`)
VALUES
	('8e129b62ce710ea03d1f698a607419eb',1338111970,'C:10:\"Model_User\":816:{a:7:{s:18:\"_primary_key_value\";s:1:\"1\";s:7:\"_object\";a:9:{s:2:\"id\";s:1:\"1\";s:5:\"email\";s:16:\"anton@lodoss.org\";s:8:\"password\";s:64:\"9117e65bc9a6a1ed724f2302287f7aa6a8fcff72cb44fb6a51e667e2d523e517\";s:6:\"logins\";O:19:\"Database_Expression\":2:{s:14:\"\0*\0_parameters\";a:0:{}s:9:\"\0*\0_value\";s:10:\"logins + 1\";}s:10:\"last_login\";i:1338111970;s:6:\"status\";s:1:\"1\";s:9:\"firstname\";s:5:\"Anton\";s:8:\"lastname\";s:6:\"Repjov\";s:8:\"username\";N;}s:8:\"_changed\";a:0:{}s:7:\"_loaded\";b:1;s:6:\"_saved\";b:1;s:8:\"_sorting\";N;s:16:\"_original_values\";a:9:{s:2:\"id\";s:1:\"1\";s:5:\"email\";s:16:\"anton@lodoss.org\";s:8:\"password\";s:64:\"9117e65bc9a6a1ed724f2302287f7aa6a8fcff72cb44fb6a51e667e2d523e517\";s:6:\"logins\";r:7;s:10:\"last_login\";i:1338111970;s:6:\"status\";s:1:\"1\";s:9:\"firstname\";s:5:\"Anton\";s:8:\"lastname\";s:6:\"Repjov\";s:8:\"username\";N;}}}'),
	('d25b6d97a040bb8ae199057547a58faf',1338112059,'C:10:\"Model_User\":713:{a:7:{s:18:\"_primary_key_value\";s:1:\"7\";s:7:\"_object\";a:9:{s:2:\"id\";s:1:\"7\";s:5:\"email\";s:15:\"stas@lodoss.org\";s:8:\"password\";s:64:\"ea6e5bc626d1596fe93325a2abb8921b657aea9723be170f429c3530546dedf0\";s:6:\"logins\";s:1:\"0\";s:10:\"last_login\";N;s:6:\"status\";s:1:\"1\";s:9:\"firstname\";s:4:\"Ivan\";s:8:\"lastname\";s:8:\"Sklyarov\";s:8:\"username\";N;}s:8:\"_changed\";a:0:{}s:7:\"_loaded\";b:1;s:6:\"_saved\";b:0;s:8:\"_sorting\";N;s:16:\"_original_values\";a:9:{s:2:\"id\";s:1:\"7\";s:5:\"email\";s:15:\"stas@lodoss.org\";s:8:\"password\";s:64:\"ea6e5bc626d1596fe93325a2abb8921b657aea9723be170f429c3530546dedf0\";s:6:\"logins\";s:1:\"0\";s:10:\"last_login\";N;s:6:\"status\";s:1:\"1\";s:9:\"firstname\";s:4:\"Ivan\";s:8:\"lastname\";s:8:\"Sklyarov\";s:8:\"username\";N;}}}'),
	('6b53dbf167ede40098d0b750b837ab9c',1338112140,'C:10:\"Model_User\":816:{a:7:{s:18:\"_primary_key_value\";s:1:\"1\";s:7:\"_object\";a:9:{s:2:\"id\";s:1:\"1\";s:5:\"email\";s:16:\"anton@lodoss.org\";s:8:\"password\";s:64:\"9117e65bc9a6a1ed724f2302287f7aa6a8fcff72cb44fb6a51e667e2d523e517\";s:6:\"logins\";O:19:\"Database_Expression\":2:{s:14:\"\0*\0_parameters\";a:0:{}s:9:\"\0*\0_value\";s:10:\"logins + 1\";}s:10:\"last_login\";i:1338112140;s:6:\"status\";s:1:\"1\";s:9:\"firstname\";s:5:\"Anton\";s:8:\"lastname\";s:6:\"Repjov\";s:8:\"username\";N;}s:8:\"_changed\";a:0:{}s:7:\"_loaded\";b:1;s:6:\"_saved\";b:1;s:8:\"_sorting\";N;s:16:\"_original_values\";a:9:{s:2:\"id\";s:1:\"1\";s:5:\"email\";s:16:\"anton@lodoss.org\";s:8:\"password\";s:64:\"9117e65bc9a6a1ed724f2302287f7aa6a8fcff72cb44fb6a51e667e2d523e517\";s:6:\"logins\";r:7;s:10:\"last_login\";i:1338112140;s:6:\"status\";s:1:\"1\";s:9:\"firstname\";s:5:\"Anton\";s:8:\"lastname\";s:6:\"Repjov\";s:8:\"username\";N;}}}');

/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_tokens
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_tokens`;

CREATE TABLE `user_tokens` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `user_agent` varchar(40) NOT NULL,
  `token` varchar(40) NOT NULL,
  `type` varchar(100) NOT NULL,
  `created` int(10) unsigned NOT NULL,
  `expires` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_token` (`token`),
  KEY `fk_user_id` (`user_id`),
  CONSTRAINT `user_tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(254) NOT NULL,
  `password` varchar(64) NOT NULL,
  `logins` int(10) unsigned NOT NULL DEFAULT '0',
  `last_login` int(10) unsigned DEFAULT NULL,
  `status` int(11) DEFAULT '1',
  `firstname` varchar(50) DEFAULT NULL,
  `lastname` varchar(100) DEFAULT NULL,
  `username` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_email` (`email`),
  UNIQUE KEY `unique_username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`,`email`,`password`,`logins`,`last_login`,`status`,`firstname`,`lastname`,`username`)
VALUES
	(1,'anton@lodoss.org','9117e65bc9a6a1ed724f2302287f7aa6a8fcff72cb44fb6a51e667e2d523e517',18,1338112140,1,'Anton','Repjov',NULL),
	(7,'stas@lodoss.org','ea6e5bc626d1596fe93325a2abb8921b657aea9723be170f429c3530546dedf0',0,NULL,1,'Ivan','Sklyarov',NULL),
	(8,'bossiha-18@mail.ru','734504cfc2fd9d354d7a6affdcd81aea3c99d7be47e766a7aabea41464b863af',0,NULL,1,'Натали','Репьёва',NULL);

/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;





/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

