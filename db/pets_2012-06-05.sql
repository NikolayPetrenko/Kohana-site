# SQL Manager 2010 for MySQL 4.5.0.9
# ---------------------------------------
# Host     : localhost
# Port     : 3306
# Database : pets


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

SET FOREIGN_KEY_CHECKS=0;

DROP DATABASE IF EXISTS `pets`;

CREATE DATABASE `pets`
    CHARACTER SET 'utf8'
    COLLATE 'utf8_general_ci';

USE `pets`;

#
# Structure for the `petypes` table : 
#

CREATE TABLE `petypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(120) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

#
# Structure for the `breeds` table : 
#

CREATE TABLE `breeds` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `breed` varchar(255) DEFAULT NULL,
  `type_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `type_id` (`type_id`),
  CONSTRAINT `breeds_fk` FOREIGN KEY (`type_id`) REFERENCES `petypes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

#
# Structure for the `friends` table : 
#

CREATE TABLE `friends` (
  `me` int(11) DEFAULT NULL,
  `friend` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# Structure for the `pets` table : 
#

CREATE TABLE `pets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `description` text,
  `picture` varchar(50) DEFAULT NULL,
  `petype_id` tinyint(4) DEFAULT NULL,
  `breed_id` int(11) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

#
# Structure for the `roles` table : 
#

CREATE TABLE `roles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

#
# Structure for the `users` table : 
#

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
  `termofuse` tinyint(4) DEFAULT '0',
  `twitter_id` int(11) DEFAULT NULL,
  `hash_code` varchar(32) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_email` (`email`),
  UNIQUE KEY `unique_username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

#
# Structure for the `roles_users` table : 
#

CREATE TABLE `roles_users` (
  `user_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `fk_role_id` (`role_id`),
  CONSTRAINT `roles_users_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `roles_users_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# Structure for the `sessions` table : 
#

CREATE TABLE `sessions` (
  `session_id` varchar(32) NOT NULL,
  `last_active` int(10) unsigned NOT NULL,
  `contents` text NOT NULL,
  PRIMARY KEY (`session_id`),
  KEY `last_active` (`last_active`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Structure for the `user_tokens` table : 
#

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

#
# Data for the `petypes` table  (LIMIT 0,500)
#

INSERT INTO `petypes` (`id`, `type`) VALUES 
  (1,'Dog'),
  (2,'Cat'),
  (3,'Rat');
COMMIT;

#
# Data for the `breeds` table  (LIMIT 0,500)
#

INSERT INTO `breeds` (`id`, `breed`, `type_id`) VALUES 
  (1,'Buldog',1),
  (2,'Buldogk',1),
  (3,'Bi',3),
  (4,'Buldogt',1),
  (7,'google',1),
  (8,'красноглазая',3),
  (10,'С большими яцами',3),
  (11,'С большими яцами',1);
COMMIT;

#
# Data for the `pets` table  (LIMIT 0,500)
#

INSERT INTO `pets` (`id`, `name`, `description`, `picture`, `petype_id`, `breed_id`, `dob`, `user_id`) VALUES 
  (16,'456','sdf','72fff8974d858ae85cab2671198c94c2.jpg',3,9,'2012-06-05',7),
  (18,'test','sdf',NULL,1,1,'2012-06-05',7);
COMMIT;

#
# Data for the `roles` table  (LIMIT 0,500)
#

INSERT INTO `roles` (`id`, `name`, `description`) VALUES 
  (1,'login','Login privileges, granted after account confirmation'),
  (2,'admin','Administrative user, has access to everything.');
COMMIT;

#
# Data for the `users` table  (LIMIT 0,500)
#

INSERT INTO `users` (`id`, `email`, `password`, `logins`, `last_login`, `status`, `firstname`, `lastname`, `username`, `termofuse`, `twitter_id`, `hash_code`, `address`, `phone`, `dob`) VALUES 
  (7,'stas@lodoss.org','9d076e5b6cf77106686c3017171ee5fcb67a84f5da238f0b3836a2819478f0a8',4,1338890688,1,'Ivan','Sklyarov',NULL,1,NULL,NULL,'','','0000-00-00');
COMMIT;

#
# Data for the `roles_users` table  (LIMIT 0,500)
#

INSERT INTO `roles_users` (`user_id`, `role_id`) VALUES 
  (7,1);
COMMIT;

#
# Data for the `sessions` table  (LIMIT 0,500)
#

INSERT INTO `sessions` (`session_id`, `last_active`, `contents`) VALUES 
  ('07d7949bada2cd6a3b03447ccaad482f',1338890688,'C:10:\"Model_User\":937:{a:7:{s:18:\"_primary_key_value\";s:1:\"7\";s:7:\"_object\";a:15:{s:2:\"id\";s:1:\"7\";s:5:\"email\";s:15:\"stas@lodoss.org\";s:8:\"password\";s:64:\"9d076e5b6cf77106686c3017171ee5fcb67a84f5da238f0b3836a2819478f0a8\";s:6:\"logins\";i:4;s:10:\"last_login\";i:1338890688;s:6:\"status\";s:1:\"1\";s:9:\"firstname\";s:4:\"Ivan\";s:8:\"lastname\";s:8:\"Sklyarov\";s:8:\"username\";N;s:9:\"termofuse\";s:1:\"1\";s:10:\"twitter_id\";N;s:9:\"hash_code\";N;s:7:\"address\";N;s:5:\"phone\";N;s:3:\"dob\";N;}s:8:\"_changed\";a:0:{}s:7:\"_loaded\";b:1;s:6:\"_saved\";b:1;s:8:\"_sorting\";N;s:16:\"_original_values\";a:15:{s:2:\"id\";s:1:\"7\";s:5:\"email\";s:15:\"stas@lodoss.org\";s:8:\"password\";s:64:\"9d076e5b6cf77106686c3017171ee5fcb67a84f5da238f0b3836a2819478f0a8\";s:6:\"logins\";i:4;s:10:\"last_login\";i:1338890688;s:6:\"status\";s:1:\"1\";s:9:\"firstname\";s:4:\"Ivan\";s:8:\"lastname\";s:8:\"Sklyarov\";s:8:\"username\";N;s:9:\"termofuse\";s:1:\"1\";s:10:\"twitter_id\";N;s:9:\"hash_code\";N;s:7:\"address\";N;s:5:\"phone\";N;s:3:\"dob\";N;}}}'),
  ('cbfb591a280b0e4b12f8f9660f03406c',1338873570,'C:10:\"Model_User\":937:{a:7:{s:18:\"_primary_key_value\";s:1:\"7\";s:7:\"_object\";a:15:{s:2:\"id\";s:1:\"7\";s:5:\"email\";s:15:\"stas@lodoss.org\";s:8:\"password\";s:64:\"9d076e5b6cf77106686c3017171ee5fcb67a84f5da238f0b3836a2819478f0a8\";s:6:\"logins\";i:3;s:10:\"last_login\";i:1338873570;s:6:\"status\";s:1:\"1\";s:9:\"firstname\";s:4:\"Ivan\";s:8:\"lastname\";s:8:\"Sklyarov\";s:8:\"username\";N;s:9:\"termofuse\";s:1:\"1\";s:10:\"twitter_id\";N;s:9:\"hash_code\";N;s:7:\"address\";N;s:5:\"phone\";N;s:3:\"dob\";N;}s:8:\"_changed\";a:0:{}s:7:\"_loaded\";b:1;s:6:\"_saved\";b:1;s:8:\"_sorting\";N;s:16:\"_original_values\";a:15:{s:2:\"id\";s:1:\"7\";s:5:\"email\";s:15:\"stas@lodoss.org\";s:8:\"password\";s:64:\"9d076e5b6cf77106686c3017171ee5fcb67a84f5da238f0b3836a2819478f0a8\";s:6:\"logins\";i:3;s:10:\"last_login\";i:1338873570;s:6:\"status\";s:1:\"1\";s:9:\"firstname\";s:4:\"Ivan\";s:8:\"lastname\";s:8:\"Sklyarov\";s:8:\"username\";N;s:9:\"termofuse\";s:1:\"1\";s:10:\"twitter_id\";N;s:9:\"hash_code\";N;s:7:\"address\";N;s:5:\"phone\";N;s:3:\"dob\";N;}}}'),
  ('d3c24d9c47f9961e656600ff8e5ad677',1338813174,'C:10:\"Model_User\":937:{a:7:{s:18:\"_primary_key_value\";s:1:\"7\";s:7:\"_object\";a:15:{s:2:\"id\";s:1:\"7\";s:5:\"email\";s:15:\"stas@lodoss.org\";s:8:\"password\";s:64:\"9d076e5b6cf77106686c3017171ee5fcb67a84f5da238f0b3836a2819478f0a8\";s:6:\"logins\";i:2;s:10:\"last_login\";i:1338813174;s:6:\"status\";s:1:\"1\";s:9:\"firstname\";s:4:\"Ivan\";s:8:\"lastname\";s:8:\"Sklyarov\";s:8:\"username\";N;s:9:\"termofuse\";s:1:\"1\";s:10:\"twitter_id\";N;s:9:\"hash_code\";N;s:7:\"address\";N;s:5:\"phone\";N;s:3:\"dob\";N;}s:8:\"_changed\";a:0:{}s:7:\"_loaded\";b:1;s:6:\"_saved\";b:1;s:8:\"_sorting\";N;s:16:\"_original_values\";a:15:{s:2:\"id\";s:1:\"7\";s:5:\"email\";s:15:\"stas@lodoss.org\";s:8:\"password\";s:64:\"9d076e5b6cf77106686c3017171ee5fcb67a84f5da238f0b3836a2819478f0a8\";s:6:\"logins\";i:2;s:10:\"last_login\";i:1338813174;s:6:\"status\";s:1:\"1\";s:9:\"firstname\";s:4:\"Ivan\";s:8:\"lastname\";s:8:\"Sklyarov\";s:8:\"username\";N;s:9:\"termofuse\";s:1:\"1\";s:10:\"twitter_id\";N;s:9:\"hash_code\";N;s:7:\"address\";N;s:5:\"phone\";N;s:3:\"dob\";N;}}}'),
  ('fb9f51ec896fe953945dc8a6d82a4773',1338814148,'C:10:\"Model_User\":955:{a:7:{s:18:\"_primary_key_value\";s:1:\"7\";s:7:\"_object\";a:15:{s:2:\"id\";s:1:\"7\";s:5:\"email\";s:15:\"stas@lodoss.org\";s:8:\"password\";s:64:\"9d076e5b6cf77106686c3017171ee5fcb67a84f5da238f0b3836a2819478f0a8\";s:6:\"logins\";s:1:\"1\";s:10:\"last_login\";s:10:\"1338794835\";s:6:\"status\";s:1:\"1\";s:9:\"firstname\";s:4:\"Ivan\";s:8:\"lastname\";s:8:\"Sklyarov\";s:8:\"username\";N;s:9:\"termofuse\";s:1:\"1\";s:10:\"twitter_id\";N;s:9:\"hash_code\";N;s:7:\"address\";N;s:5:\"phone\";N;s:3:\"dob\";N;}s:8:\"_changed\";a:0:{}s:7:\"_loaded\";b:1;s:6:\"_saved\";b:0;s:8:\"_sorting\";N;s:16:\"_original_values\";a:15:{s:2:\"id\";s:1:\"7\";s:5:\"email\";s:15:\"stas@lodoss.org\";s:8:\"password\";s:64:\"9d076e5b6cf77106686c3017171ee5fcb67a84f5da238f0b3836a2819478f0a8\";s:6:\"logins\";s:1:\"1\";s:10:\"last_login\";s:10:\"1338794835\";s:6:\"status\";s:1:\"1\";s:9:\"firstname\";s:4:\"Ivan\";s:8:\"lastname\";s:8:\"Sklyarov\";s:8:\"username\";N;s:9:\"termofuse\";s:1:\"1\";s:10:\"twitter_id\";N;s:9:\"hash_code\";N;s:7:\"address\";N;s:5:\"phone\";N;s:3:\"dob\";N;}}}');
COMMIT;



/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;