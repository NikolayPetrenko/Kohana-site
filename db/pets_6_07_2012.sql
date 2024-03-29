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
# Structure for the `pet_types` table : 
#

CREATE TABLE `pet_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(120) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

#
# Structure for the `breeds` table : 
#

CREATE TABLE `breeds` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `breed` varchar(255) DEFAULT NULL,
  `type_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `type_id` (`type_id`),
  CONSTRAINT `breeds_fk` FOREIGN KEY (`type_id`) REFERENCES `pet_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1234 DEFAULT CHARSET=utf8;

#
# Structure for the `users` table : 
#

CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `facebook_id` varchar(50) DEFAULT NULL,
  `twitter_id` varchar(50) DEFAULT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `last_login` int(10) unsigned DEFAULT NULL,
  `email` varchar(254) NOT NULL,
  `password` varchar(64) NOT NULL,
  `logins` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) DEFAULT '1',
  `lastname` varchar(100) DEFAULT NULL,
  `username` varchar(32) DEFAULT NULL,
  `termofuse` tinyint(4) DEFAULT '0',
  `hash_code` varchar(32) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `primary_phone` varchar(20) DEFAULT NULL,
  `secondary_phone` varchar(20) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `avatar` varchar(50) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `zip` varchar(20) DEFAULT NULL,
  `device_token` varchar(150) DEFAULT NULL,
  `facebook_token` varchar(200) DEFAULT NULL,
  `twitter_token` varchar(200) DEFAULT NULL,
  `twitter_secret` varchar(200) DEFAULT NULL,
  `facebook_expire_date` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_email` (`email`),
  UNIQUE KEY `unique_username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=90 DEFAULT CHARSET=utf8;

#
# Structure for the `pets` table : 
#

CREATE TABLE `pets` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `picture` varchar(50) DEFAULT NULL,
  `type_id` tinyint(4) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `user_id` int(11) unsigned DEFAULT NULL,
  `description` text,
  `text_status` varchar(255) DEFAULT NULL,
  `breed_id` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `breed_id` (`breed_id`),
  CONSTRAINT `pets_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `pets_fk1` FOREIGN KEY (`breed_id`) REFERENCES `breeds` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=261 DEFAULT CHARSET=utf8;

#
# Structure for the `feeds` table : 
#

CREATE TABLE `feeds` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned DEFAULT NULL,
  `pet_id` int(11) unsigned DEFAULT NULL,
  `code_name` varchar(50) DEFAULT NULL,
  `feed` text,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `pet_id` (`pet_id`),
  CONSTRAINT `feeds_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `feeds_fk1` FOREIGN KEY (`pet_id`) REFERENCES `pets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=295 DEFAULT CHARSET=utf8;

#
# Structure for the `friendships` table : 
#

CREATE TABLE `friendships` (
  `user_id` int(11) unsigned NOT NULL,
  `friend_id` int(11) unsigned NOT NULL,
  `accepted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`user_id`,`friend_id`),
  KEY `friendships_ibfk_1` (`user_id`),
  KEY `friendships_ibfk_2` (`friend_id`),
  CONSTRAINT `friendships_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `friendships_ibfk_2` FOREIGN KEY (`friend_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# Structure for the `location_categories` table : 
#

CREATE TABLE `location_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

#
# Structure for the `locations` table : 
#

CREATE TABLE `locations` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `description` text,
  `picture` varchar(40) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `point` point NOT NULL,
  `status` tinyint(1) DEFAULT '1',
  `isConfirm` tinyint(1) DEFAULT '0' COMMENT '0 - not confirm, 1 - confirm',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8;

#
# Structure for the `location_checkins` table : 
#

CREATE TABLE `location_checkins` (
  `location_id` int(11) unsigned DEFAULT NULL,
  `pet_id` int(11) unsigned DEFAULT NULL,
  `user_id` int(11) unsigned DEFAULT NULL,
  KEY `location_id` (`location_id`),
  KEY `user_id` (`pet_id`),
  CONSTRAINT `location_checkins_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `location_checkins_fk1` FOREIGN KEY (`pet_id`) REFERENCES `pets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# Structure for the `location_confirms` table : 
#

CREATE TABLE `location_confirms` (
  `location_id` int(11) unsigned DEFAULT NULL,
  `user_id` int(11) unsigned NOT NULL,
  KEY `location_id` (`location_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `location_confirms_fk1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `location_confirms_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# Structure for the `pet_finds` table : 
#

CREATE TABLE `pet_finds` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pet_id` int(11) unsigned DEFAULT NULL,
  `user_id` int(11) unsigned DEFAULT NULL,
  `address` text,
  `point` point NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

#
# Structure for the `pet_losts` table : 
#

CREATE TABLE `pet_losts` (
  `pet_id` int(11) unsigned DEFAULT NULL,
  `last_seen` varchar(255) DEFAULT NULL,
  `point` point NOT NULL,
  `pdf` varchar(50) DEFAULT NULL,
  KEY `pet_id` (`pet_id`),
  CONSTRAINT `pet_losts_fk` FOREIGN KEY (`pet_id`) REFERENCES `pets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# Structure for the `pet_photos` table : 
#

CREATE TABLE `pet_photos` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pet_id` int(11) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `pet_id` (`pet_id`),
  CONSTRAINT `pet_photos_fk` FOREIGN KEY (`pet_id`) REFERENCES `pets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

#
# Structure for the `pet_tags` table : 
#

CREATE TABLE `pet_tags` (
  `pet_id` int(11) unsigned DEFAULT NULL,
  `stripe_token` varchar(255) DEFAULT NULL,
  `qrcode` varchar(50) DEFAULT NULL,
  KEY `pet_id` (`pet_id`),
  CONSTRAINT `pet_tags_fk` FOREIGN KEY (`pet_id`) REFERENCES `pets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
# Structure for the `settings` table : 
#

CREATE TABLE `settings` (
  `key` varchar(50) NOT NULL,
  `value` text,
  `label` varchar(255) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Structure for the `user_locations` table : 
#

CREATE TABLE `user_locations` (
  `user_id` int(11) unsigned DEFAULT NULL,
  `point` point DEFAULT NULL,
  KEY `user_id` (`user_id`),
  KEY `point` (`point`(25)),
  CONSTRAINT `user_locations_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
# Structure for the `user_unknowns` table : 
#

CREATE TABLE `user_unknowns` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned DEFAULT NULL,
  `picture` varchar(50) NOT NULL,
  `description` text,
  `address` text,
  `point` point DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `user_unknowns_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

#
# Definition for the `distance` function : 
#

CREATE DEFINER = 'root'@'localhost' FUNCTION `distance`(
        a POINT,
        b POINT
    )
    RETURNS double
    DETERMINISTIC
    CONTAINS SQL
    SQL SECURITY DEFINER
    COMMENT ''
BEGIN
RETURN GLength(LINESTRING(a, b));
END;

#
# Definition for the `alerts` view : 
#

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW alerts AS 
  select 
    `PF`.`id` AS `about_id`,
    `F`.`friend_id` AS `friend_id`,
    `U`.`id` AS `id`,
    `PF`.`point` AS `point`,
    'find' AS `status` 
  from 
    ((`pet_finds` `PF` left join `users` `U` on((`PF`.`user_id` = `U`.`id`))) left join `friendships` `F` on((`F`.`user_id` = `U`.`id`))) 
  where 
    (`F`.`accepted` = 1) 
  group by 
    `PF`.`id` union 
  select 
    `PL`.`pet_id` AS `about_id`,
    `F`.`friend_id` AS `friend_id`,
    `U`.`id` AS `id`,
    `PL`.`point` AS `point`,
    'lost' AS `status` 
  from 
    (((`pet_losts` `PL` left join `pets` `P` on((`P`.`id` = `PL`.`pet_id`))) join `users` `U` on((`P`.`user_id` = `U`.`id`))) left join `friendships` `F` on((`F`.`user_id` = `U`.`id`))) 
  where 
    (`F`.`accepted` = 1) 
  group by 
    `PL`.`pet_id` union 
  select 
    `UUNK`.`id` AS `about_id`,
    `F`.`friend_id` AS `friend_id`,
    `U`.`id` AS `id`,
    `UUNK`.`point` AS `point`,
    'unknown' AS `status` 
  from 
    ((`user_unknowns` `UUNK` left join `users` `U` on((`U`.`id` = `UUNK`.`user_id`))) left join `friendships` `F` on((`F`.`user_id` = `U`.`id`))) 
  group by 
    `UUNK`.`id`;

#
# Data for the `pet_types` table  (LIMIT 0,500)
#

INSERT INTO `pet_types` (`id`, `type`) VALUES 
  (1,'Dog'),
  (2,'Cat'),
  (3,'Bird'),
  (4,'Ampibian'),
  (5,'Reptile'),
  (6,'Fish'),
  (7,'Horse'),
  (8,'Other');
COMMIT;

#
# Data for the `breeds` table  (LIMIT 0,500)
#

INSERT INTO `breeds` (`id`, `breed`, `type_id`) VALUES 
  (5,'Affenpinscher',1),
  (6,'Afghan Hound',1),
  (7,'Airedale Terrier',1),
  (8,'Akita',1),
  (9,'Alaskan Malamute',1),
  (10,'American English Coonhound',1),
  (11,'American Eskimo Dog',1),
  (12,'American Foxhound',1),
  (13,'American Staffordshire Terrier',1),
  (14,'American Water Spaniel',1),
  (15,'Anatolian Shepherd Dog',1),
  (16,'Australian Cattle Dog',1),
  (17,'Australian Shepherd',1),
  (18,'Australian Terrier',1),
  (19,'Basenji',1),
  (20,'Basset Hound',1),
  (21,'Beagle',1),
  (22,'Bearded Collie',1),
  (23,'Beauceron',1),
  (24,'Bedlington Terrier',1),
  (25,'Belgian Malinois',1),
  (26,'Belgian Sheepdog',1),
  (27,'Belgian Tervuren',1),
  (28,'Bernese Mountain Dog',1),
  (29,'Bichon Frise',1),
  (30,'Black and Tan Coonhound',1),
  (31,'Black Russian Terrier',1),
  (32,'Bloodhound',1),
  (33,'Bluetick Coonhound',1),
  (34,'Border Collie',1),
  (35,'Border Terrier',1),
  (36,'Borzoi',1),
  (37,'Boston Terrier',1),
  (38,'Bouvier des Flandres',1),
  (39,'Boxer',1),
  (40,'Boykin Spaniel',1),
  (41,'Briard',1),
  (42,'Brittany',1),
  (43,'Brussels Griffon',1),
  (44,'Bull Terrier',1),
  (45,'Bulldog',1),
  (46,'Bullmastiff',1),
  (47,'Cairn Terrier',1),
  (48,'Canaan Dog',1),
  (49,'Cane Corso',1),
  (50,'Cardigan Welsh Corgi',1),
  (51,'Cavalier King Charles Spaniel',1),
  (52,'Cesky Terrier',1),
  (53,'Chesapeake Bay Retriever',1),
  (54,'Chihuahua',1),
  (55,'Chinese Crested',1),
  (56,'Chinese Shar-Pei',1),
  (57,'Chow Chow',1),
  (58,'Clumber Spaniel',1),
  (59,'Cocker Spaniel',1),
  (60,'Collie',1),
  (61,'Curly-Coated Retriever',1),
  (62,'Dachshund',1),
  (63,'Dalmatian',1),
  (64,'Dandie Dinmont Terrier',1),
  (65,'Doberman Pinscher',1),
  (66,'Dogue de Bordeaux',1),
  (67,'English Cocker Spaniel',1),
  (68,'English Foxhound',1),
  (69,'English Setter',1),
  (70,'English Springer Spaniel',1),
  (71,'English Toy Spaniel',1),
  (72,'Entlebucher Mountain Dog',1),
  (73,'Field Spaniel',1),
  (74,'Finnish Lapphund',1),
  (75,'Finnish Spitz',1),
  (76,'Flat-Coated Retriever',1),
  (77,'French Bulldog',1),
  (78,'German Pinscher',1),
  (79,'German Shepherd Dog',1),
  (80,'German Shorthaired Pointer',1),
  (81,'German Wirehaired Pointer',1),
  (82,'Giant Schnauzer',1),
  (83,'Glen of Imaal Terrier',1),
  (84,'Golden Retriever',1),
  (85,'Gordon Setter',1),
  (86,'Great Dane',1),
  (87,'Great Pyrenees',1),
  (88,'Greater Swiss Mountain Dog',1),
  (89,'Greyhound',1),
  (90,'Harrier',1),
  (91,'Havanese',1),
  (92,'Ibizan Hound',1),
  (93,'Icelandic Sheepdog',1),
  (94,'Irish Red and White Setter',1),
  (95,'Irish Setter',1),
  (96,'Irish Terrier',1),
  (97,'Irish Water Spaniel',1),
  (98,'Irish Wolfhound',1),
  (99,'Italian Greyhound',1),
  (100,'Japanese Chin',1),
  (101,'Keeshond',1),
  (102,'Kerry Blue Terrier',1),
  (103,'Komondor',1),
  (104,'Kuvasz',1),
  (105,'Labrador Retriever',1),
  (106,'Lakeland Terrier',1),
  (107,'Leonberger',1),
  (108,'Lhasa Apso',1),
  (109,'LÃ¶wchen',1),
  (110,'Maltese',1),
  (111,'Manchester Terrier',1),
  (112,'Mastiff',1),
  (113,'Miniature Bull Terrier',1),
  (114,'Miniature Pinscher',1),
  (115,'Miniature Schnauzer',1),
  (116,'Neapolitan Mastiff',1),
  (117,'Newfoundland',1),
  (118,'Norfolk Terrier',1),
  (119,'Norwegian Buhund',1),
  (120,'Norwegian Elkhound',1),
  (121,'Norwegian Lundehund',1),
  (122,'Norwich Terrier',1),
  (123,'Nova Scotia Duck Tolling Retriever',1),
  (124,'Old English Sheepdog',1),
  (125,'Otterhound',1),
  (126,'Papillon',1),
  (127,'Parson Russell Terrier',1),
  (128,'Pekingese',1),
  (129,'Pembroke Welsh Corgi',1),
  (130,'Petit Basset Griffon VendÃ©en',1),
  (131,'Pharaoh Hound',1),
  (132,'Plott',1),
  (133,'Pointer',1),
  (134,'Polish Lowland Sheepdog',1),
  (135,'Pomeranian',1),
  (136,'Poodle',1),
  (137,'Portuguese Water Dog',1),
  (138,'Pug',1),
  (139,'Puli',1),
  (140,'Pyrenean Shepherd',1),
  (141,'Redbone Coonhound',1),
  (142,'Rhodesian Ridgeback',1),
  (143,'Rottweiler',1),
  (144,'Russell Terrier',1),
  (145,'Saint Bernard',1),
  (146,'Saluki',1),
  (147,'Samoyed',1),
  (148,'Schipperke',1),
  (149,'Scottish Deerhound',1),
  (150,'Scottish Terrier',1),
  (151,'Sealyham Terrier',1),
  (152,'Shetland Sheepdog',1),
  (153,'Shiba Inu',1),
  (154,'Shih Tzu',1),
  (155,'Siberian Husky',1),
  (156,'Silky Terrier',1),
  (157,'Skye Terrier',1),
  (158,'Smooth Fox Terrier',1),
  (159,'Soft Coated Wheaten Terrier',1),
  (160,'Spinone Italiano',1),
  (161,'Staffordshire Bull Terrier',1),
  (162,'Standard Schnauzer',1),
  (163,'Sussex Spaniel',1),
  (164,'Swedish Vallhund',1),
  (165,'Tibetan Mastiff',1),
  (166,'Tibetan Spaniel',1),
  (167,'Tibetan Terrier',1),
  (168,'Toy Fox Terrier',1),
  (169,'Treeing Walker Coonhound',1),
  (170,'Vizsla',1),
  (171,'Weimaraner',1),
  (172,'Welsh Springer Spaniel',1),
  (173,'Welsh Terrier',1),
  (174,'West Highland White Terrier',1),
  (175,'Whippet',1),
  (176,'Wire Fox Terrier',1),
  (177,'Wirehaired Pointing Griffon',1),
  (178,'Xoloitzcuintli',1),
  (179,'Yorkshire Terrier',1),
  (180,'Abyssinian',2),
  (181,'American Bobtail',2),
  (182,'American Curl',2),
  (183,'American Shorthair',2),
  (184,'American Wirehair',2),
  (185,'Balinese',2),
  (186,'Birman Bombay',2),
  (187,'British Shorthair',2),
  (188,'Burmese',2),
  (189,'Chartreux',2),
  (190,'Colorpoint Shorthair',2),
  (191,'Cornish Rex',2),
  (192,'Devon Rex',2),
  (193,'Egyptian Mau',2),
  (194,'European Burmese',2),
  (195,'Exotic',2),
  (196,'Havana Brown',2),
  (197,'Japanese Bobtail',2),
  (198,'Korat',2),
  (199,'LaPerm',2),
  (200,'Maine Coon',2),
  (201,'Manx',2),
  (202,'Norwegian Forest Cat',2),
  (203,'Ocicat',2),
  (204,'Oriental',2),
  (205,'Persian',2),
  (206,'RagaMuffin',2),
  (207,'Ragdoll',2),
  (208,'Russian Blue',2),
  (209,'Scottish Fold',2),
  (210,'Selkirk Rex',2),
  (211,'Siamese',2),
  (212,'Siberian',2),
  (213,'Singapura',2),
  (214,'Somali',2),
  (215,'Sphynx',2),
  (216,'Tonkinese',2),
  (217,'Turkish Angora',2),
  (218,'Turkish Van',2),
  (355,'African Grey Parrot',3),
  (356,'Amazon Parrot',3),
  (357,'Blue and Gold Macaw',3),
  (358,'Blue-fronted Amazon Parrot',3),
  (359,'Bunting',3),
  (360,'Button Quail',3),
  (361,'Caique',3),
  (362,'Canary',3),
  (363,'Cockatiel',3),
  (364,'Cockatoo',3),
  (365,'Conure',3),
  (366,'Corella Tanimbar',3),
  (367,'Dove',3),
  (368,'Eclectus Parrot',3),
  (369,'Eye Ring Lovebird',3),
  (370,'Finch',3),
  (371,'Gouldian Finch',3),
  (372,'Green-cheeked and Maroon-belly Conures',3),
  (373,'Hardbill',3),
  (374,'Jenday Conure',3),
  (375,'Large Hookbill',3),
  (376,'Lory And Lorikeet',3),
  (377,'Lovebird',3),
  (378,'Macaw',3),
  (379,'Medium Sulfur-Crested Cockatoo',3),
  (380,'Military Macaw',3),
  (381,'Moluccan Cockatoo',3),
  (382,'Myers Parrot',3),
  (383,'Nanday Conure',3),
  (384,'Orange Weaver Finch',3),
  (385,'Parakeet (Budgerigar)',3),
  (386,'Parakeet (Medium-sized)',3),
  (387,'Parrotlet',3),
  (388,'Peachfaced Lovebird',3),
  (389,'Pionus Parrot',3),
  (390,'Quaker Parakeet',3),
  (391,'Red-bellied Parrot',3),
  (392,'Red-Factor Canary',3),
  (393,'Red-shouldered Parrot',3),
  (394,'Ringneck Parakeet',3),
  (395,'Rose-Breasted Cockatoo',3),
  (396,'Scarlet Macaw',3),
  (397,'Senegal Parrot',3),
  (398,'Severe Macaw',3),
  (399,'Small Hookbill',3),
  (400,'Society Finch',3),
  (401,'Spice Finch',3),
  (402,'Sun Conure',3),
  (403,'Umbrella Cockatoo',3),
  (404,'White-fronted Parrot',3),
  (405,'Yellow Canary',3),
  (406,'Zebra Finch',3),
  (530,'Fire-bellied Toad',4),
  (531,'Monkey Tree Frog',4),
  (532,'Pac-Man Frog',4),
  (533,'Salamanders',4),
  (534,'Newt',4),
  (535,'Terrestrial Frog',4),
  (536,'Toad',4),
  (537,'Tree Frog',4),
  (705,'Anole',5),
  (706,'Bearded Dragon',5),
  (707,'Blue-Tongued Skink',5),
  (708,'Gecko',5),
  (709,'Giant Day Gecko',5),
  (710,'Green Iguana',5),
  (711,'Iguanas',5),
  (712,'Panther Chameleon',5),
  (713,'Plumed Basilisk',5),
  (714,'Savannah Monitor',5),
  (715,'Steppe Runner Lizard',5),
  (716,'Veiled Chameleon',5),
  (717,'Black Rat Snake',5),
  (718,'Corn Snake',5),
  (719,'Garter Snake',5),
  (720,'Great Plains Rat Snake',5),
  (721,'Kingsnake',5),
  (722,'Python',5),
  (723,'Rosy Boa',5),
  (724,'Sunbeam Snake',5),
  (725,'Greek Tortoise',5),
  (726,'Sulcata Tortoise',5),
  (727,'Eastern Box Turtle',5),
  (728,'Matamata',5),
  (729,'Red-eared Slider Turtle',5),
  (730,'African Dwarf Clawed Frogs',5),
  (731,'American Green Tree Frog',5),
  (732,'Australian Green Tree Frog',5),
  (733,'Ornate Horned Frog',5),
  (734,'Fire Belly Toad',5),
  (880,'African Cichlids',6),
  (881,'Cichlids ',6),
  (882,'Angelfish',6),
  (883,'Barb',6),
  (884,'Betta',6),
  (885,'Bichir',6),
  (886,'Catfish',6),
  (887,'Danios',6),
  (888,'Minnow',6),
  (889,'Discus',6),
  (890,'Goldfish',6),
  (891,'Oddball',6),
  (892,'Loaches',6),
  (893,'Molies',6),
  (894,'Planties',6),
  (895,'Piecos',6),
  (896,'Rainbow Fish',6),
  (897,'Rasboras',6),
  (898,'Shark',6),
  (899,'Suckermouth',6),
  (900,'Swordtails',6),
  (901,'Tetras',6),
  (902,'Brackfish',6),
  (1055,'American Saddlebred',7),
  (1056,'Andalusian Horses ',7),
  (1057,'Appaloosa Horses ',7),
  (1058,'Arabian Horses ',7),
  (1059,'Friesian Horses ',7),
  (1060,'Â Gypsy Vanner',7),
  (1061,'Â Miniature Horse',7),
  (1062,'Missouri Fox Trotter',7),
  (1063,'Â Morgan',7),
  (1064,'Mustangs',7),
  (1065,'Palomino',7),
  (1066,'Pony',7),
  (1067,'Passo Fino ',7),
  (1068,'Quarter Horse',7),
  (1069,'Shire',7),
  (1070,'Thoroughbred',7),
  (1230,'Tarantula',8),
  (1231,'Spider',8),
  (1232,'Hamster',8),
  (1233,'Farret',8);
COMMIT;

#
# Data for the `users` table  (LIMIT 0,500)
#

INSERT INTO `users` (`id`, `facebook_id`, `twitter_id`, `firstname`, `last_login`, `email`, `password`, `logins`, `status`, `lastname`, `username`, `termofuse`, `hash_code`, `address`, `primary_phone`, `secondary_phone`, `dob`, `avatar`, `state`, `city`, `zip`, `device_token`, `facebook_token`, `twitter_token`, `twitter_secret`, `facebook_expire_date`) VALUES 
  (23,'100001160143751',NULL,'aaa',1340706355,'aiiejibcuhka@gmail.com','692b539272e099809a9a974fea9a2e1d16c2d0ffbe230d18ffae5e4056714b3e',102,1,'qqq',NULL,1,NULL,'','',NULL,'2012-06-14',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
  (26,NULL,'226103341','Andrey',1339596609,'sadasd@asa.ru','f3f6fabe9c23339728a6abaee8677acf55002b9bb31312917ff2df35817c7997',99,1,'Tereschenko',NULL,1,NULL,'','',NULL,'2012-06-14',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
  (29,NULL,'564088400','David',1341581865,'dave@hailstudio.com','2eab30b696523fd399566e5932f61bc7ae9999ab65e9cd2febf3e25d67fbb107',82,1,'Latshaw',NULL,1,NULL,'511 W. Blount St.','8502913592','','1985-09-19','16fc0ed0b5779bdce30c0cdbd1e853de.jpg','Florida','Pensacola','32501','87cb38d7be30a7514d664f3f10693b50f563329ab06f4709e2d2cff7d50af203','AAAEYFJ0CObYBAFIdRiBbNKD7sQPYmOhLrgiZCQiZBfL20Vop02jl0fZB8ROGl03b8QCXm1gXIMZCvDFlFNws3fshF3tSvFBtSsRZB8fpzUAZDZD','564088400-X0QQXSdnhfPkjzAtbfuY3IJVl9MPPd3rx3rbxmhk','Yh5wsuiC4fCCq0G3ve7GEXkAy2ixiDt3yXG6AvTv0',NULL),
  (31,'100000831759178',NULL,'Александр',1341411120,'grandmobille@list.ru','9893c596fe5e65d777134b311a86fa00094ca10500656ec6dbd67c8a31e266dd',54,1,'Леонтьев',NULL,1,NULL,'Taganrog, ','123123','123123','2012-06-14','f8b139345d331c578ecb3e4eb23a0466.jpg','South Carolina','Taganrog','13321',NULL,NULL,NULL,NULL,NULL),
  (34,NULL,'579039794','Dmitry',1341475669,'litvdim@gmail.com','75d34ac1296711e16bda11264ce1abece7a74ba8f40e457b7b5e91050d77a608',11,1,'Litvyak',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'579039794-RafvKgYR86DVugn0bxRtvw0YgugUeWzLXihhMMYN','SkenwylxzDpB5FfW9Qv7LiPEyKlNtFjwlAktL8iUBYM',NULL),
  (35,'100003785264757','587428024','Vitaly',1341585083,'vtim23@gmail.com','5f98954de910b25eaa3e12676fdd47f8f1c695c87fabe2848e54c43d6a438a10',325,1,'Timofeev',NULL,1,'','Taganrog','123123','123123','2012-06-18',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
  (38,NULL,NULL,'Tiffany',1341497392,'accounts@hailstudio.com','2eab30b696523fd399566e5932f61bc7ae9999ab65e9cd2febf3e25d67fbb107',7,1,'Wynn',NULL,1,NULL,'511 W. Blount St.','8505725969','8502913592','2012-07-04','32d6008038d71fb2711aa21fe4042afd.jpg','Florida','Pensacola','32501',NULL,NULL,NULL,NULL,NULL),
  (74,NULL,NULL,'kkk',1341313995,'kkk@kkk.kkk','d998acc18ba05b5414cdb08989d02e3c0a760c6855acbe629c730fa691fb6aee',206,1,'kkk',NULL,1,NULL,'Test','213123','123123123','2012-06-27','cf036c39ccca299c033d398c34bc00dc.jpg','Tennessee','Aaa234','Re',NULL,NULL,NULL,NULL,NULL),
  (76,NULL,NULL,'tag_test',1341582356,'test@test.test','d998acc18ba05b5414cdb08989d02e3c0a760c6855acbe629c730fa691fb6aee',208,1,'test',NULL,1,NULL,'use address','phone','','2012-06-29','','Rhode Island','tagan','8888','ae6a8a91a660976edb81d2c9da1bbae63ac9a5c054a5ba23aaced75c61c8d433','BAAEYFJ0CObYBAHkhuZAOIrnhcIDzAJyfvNFbajpZBBcc2r5ZA9qP8ifRiEIwpfhPyLM6NR3ePbUtDVZBahRZCPqSAygvijhkjspyF9x8WgpQTABnetue3e8TaHXzchKa2FV8HDilzo2Oari3KubMq','587428024-mC5rCV2yYCO8qUx11p5GHOO32tFzCleRBEC8Yw0o','EfBfrRQnqb3QkHq5liQyFhLpRo8kFlaCXD9F7Pt2Q','1346584140.920995'),
  (77,NULL,NULL,'Peyton',1341086866,'peyco15@gmail.com','8e57995421f6d93076f4ebbff2045a6ee139bbf26b5cfa3372422506e6821be1',5,1,'Cook',NULL,1,NULL,'','','','2012-06-28',NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL),
  (78,'1242443875',NULL,'Anton',1341557212,'repjov@gmail.com','1eb0cb9bee8d8d10d50e74647dafa51707ae5a501148504d07c9df3356bc934c',3,1,'Repjov',NULL,1,NULL,'dfgfgdfg','','','2012-06-29','','','','',NULL,'AAAEYFJ0CObYBAHETG9u836tOxJGw3SC1pZCwaiOHgpXpXkSwvJFufme5ASEcw5FE29JlQcoZCS3jyH2wavn3xscU7SDZC5XhhG7J7LVxQZDZD','89926365-roSBqJBfpvKhwtfvSmw7R4QzLxNSHF4WzM5gCCJgk','kPDsqV5o5tocY9q9KGeIRMbuU3aqIh41JLwVXHfC5B0','1346654832'),
  (79,NULL,NULL,'Terry',1341239021,'Abbott_T@Yahoo.com','0a7f4ec6b63f0779f9351661e5f25021c59740f0d0723e0baea99f7ac8c1701b',3,1,'Abbott',NULL,1,'8e0a4e936b4180ec1b7a42307bfda89d','','','','2012-06-29','','','','',NULL,NULL,NULL,NULL,NULL),
  (80,'519084179',NULL,'Musab',1341044904,'musab85@hotmail.com','73fac7e139dbce645e229e38d25464bbb8d767e1c421ca16532f847b2233edaf',2,1,'Ibrahim',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
  (86,NULL,NULL,'Dmitry',1341498258,'dmitrli@yahoo.com','d998acc18ba05b5414cdb08989d02e3c0a760c6855acbe629c730fa691fb6aee',6,1,'Litvyak',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'627147659-buSAkxsept6Ti5OVXTEVSuAvl3MeBZuMuztz3Qo','INiIc83Odoh7U3Oiv1tMNz2QVxkNzHNb3bORGDxsfE',NULL),
  (87,NULL,NULL,'hhh',1341578120,'hhh@hhh.hhh','d998acc18ba05b5414cdb08989d02e3c0a760c6855acbe629c730fa691fb6aee',28,1,'hhh',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
  (89,'100003488755266',NULL,'Ivan',1341581469,'stas@lodoss.org','7af135c39c2f8eebe9cc65f8bf8a4e587a47ed7c850a57fb4829b69945700947',2,1,'Sklyarov',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
COMMIT;

#
# Data for the `pets` table  (LIMIT 0,500)
#

INSERT INTO `pets` (`id`, `name`, `picture`, `type_id`, `dob`, `user_id`, `description`, `text_status`, `breed_id`) VALUES 
  (204,'test','d01fc457b629db620d0d8b87b4123bd6.jpg',1,'2012-06-26',34,'asd',NULL,5),
  (206,'tr','2a8d7493341e3299e1788eccd369e9ae.jpg',1,'2012-06-26',34,'',NULL,5),
  (207,'pet','b363ca5e54ecf111c72422f145467240.jpg',1,'2012-06-27',74,'harrier dog',NULL,90),
  (212,'Sadie','6aab5f80d901b1e97cb6c4334808e302.jpg',1,'1998-06-27',29,'A bad ass dog!','',39),
  (213,'Kylie','49c1d242323aba5c65870a0557a2f705.jpg',1,'1999-04-13',29,'The goofiest dog in the world.','I''m ballin out of control.',39),
  (214,'David','62519d11a716507a564835170d36a7a1.jpg',8,'1985-09-19',38,'My boyfriend David.',NULL,1232),
  (215,'test',NULL,1,'2012-06-28',74,'E',NULL,67),
  (221,'test','',1,'2012-06-28',35,'e','',166),
  (222,'tag','8867231a9e84d08c615e960cb8f2c08d.jpg',1,'2012-06-28',76,'Ta',NULL,141),
  (223,'Bell','',1,'2006-04-03',77,'just shrimpin since been shrimpin, since been shrimping ',NULL,79),
  (224,'test2','1d39fe32f259c713cbd45b717ac34b70.jpg',1,'2012-06-29',76,'df',NULL,5),
  (226,'Sharik','f6574095a3888c4307bcb120146603a0.jpg',1,'2012-06-03',78,'asdfasdfasf',NULL,19),
  (227,'Parker','',1,'2012-06-29',79,'',NULL,105),
  (233,'Barbos','aa208f1b29c76b4fe11c4fe7cd99979c.jpg',1,'2012-07-03',31,'Barbos-pet',NULL,125),
  (242,'ppp','',1,'2012-07-04',35,'sf',NULL,73),
  (243,'push','80bf3a8d35670fdfc8802804f0abfa2d.jpg',1,'2012-07-04',76,'Tr',NULL,141),
  (244,'kkk',NULL,1,'2012-07-04',35,'F',NULL,75),
  (245,'qweqw',NULL,1,'2012-07-04',35,'Wqe',NULL,171),
  (246,'fgddfg',NULL,1,'2012-07-04',35,'Dfg',NULL,78),
  (247,'werwer',NULL,1,'2012-07-04',35,'R',NULL,142),
  (248,'qwe','',1,'2012-07-04',35,'E',NULL,69),
  (249,'pdf','7b0224725537119c8ab3e32405062b4b.jpg',1,'2012-07-05',76,'Zzxc',NULL,62),
  (250,'lll','49696abcc113703fccdf6e3017970f98.jpg',1,'2012-07-05',76,'F',NULL,73),
  (251,'erdfs',NULL,1,'2012-07-05',76,'R',NULL,144),
  (252,'Lizzy','8bf2257ef61291756885d58e3b8f2b5a.jpg',5,'2004-07-05',29,'A green lizard','',731),
  (253,'Rob','d8ea83e8a68a69d8809c810a1d7bfb4a.jpeg',1,'2012-07-10',78,'the best dog in the world',NULL,9),
  (255,'Ad','',1,'2012-07-06',89,'ds',NULL,5),
  (256,'Ad','',1,'2012-07-06',89,'ds',NULL,5),
  (257,'wqe','48fdde430d964194b9346cbd444c28db.jpg',1,'2012-07-06',87,'E',NULL,5),
  (258,'ooo',NULL,1,'2012-07-06',35,'P',NULL,10),
  (259,'vxcv',NULL,1,'2012-07-06',35,'V',NULL,27),
  (260,'qwe',NULL,1,'2012-07-06',35,'E',NULL,5);
COMMIT;

#
# Data for the `feeds` table  (LIMIT 0,500)
#

INSERT INTO `feeds` (`id`, `user_id`, `pet_id`, `code_name`, `feed`, `date_created`) VALUES 
  (112,34,204,'add_pet','Your friend Dmitry Litvyak add new Dog by name test','2012-06-26 13:06:29'),
  (114,34,206,'add_pet','Your friend Dmitry Litvyak add new Dog by name tr','2012-06-26 13:49:07'),
  (121,74,207,'add_pet','Your friend kkk kkk add new Dog by name pet','2012-06-27 10:43:12'),
  (132,29,212,'add_pet','Your friend David Latshaw add new Dog by name Sadie','2012-06-27 14:52:20'),
  (133,29,212,'upload_photo','David add new photo for Sadie','2012-06-27 14:55:38'),
  (134,29,213,'add_pet','Your friend David Latshaw add new Dog by name Kylie','2012-06-27 15:00:40'),
  (135,29,213,'change_pet_status','David change status for Kylie','2012-06-27 15:03:51'),
  (136,29,213,'upload_photo','David add new photo for Kylie','2012-06-27 15:04:36'),
  (137,29,213,'check_in_location','Kylie has Checked in at Bayview Park','2012-06-27 15:07:25'),
  (138,29,212,'check_in_location','Sadie has Checked in at Bayview Park','2012-06-27 15:07:25'),
  (139,29,212,'check_in_location','Sadie has Checked in at Park','2012-06-27 15:08:40'),
  (140,29,213,'check_in_location','Kylie has Checked in at Park','2012-06-27 15:08:40'),
  (141,29,212,'change_pet_status','David change status for Sadie','2012-06-27 18:28:35'),
  (142,29,212,'check_in_location','Sadie has Checked in at Vvvvvvv','2012-06-27 18:33:08'),
  (143,29,213,'check_in_location','Kylie has Checked in at Vvvvvvv','2012-06-27 18:33:08'),
  (144,29,213,'change_pet_status','David change status for Kylie','2012-06-27 18:35:23'),
  (145,29,213,'upload_photo','David add new photo for Kylie','2012-06-27 21:11:50'),
  (146,29,212,'change_pet_status','David change status for Sadie','2012-06-27 21:12:25'),
  (147,29,213,'check_in_location','Kylie has Checked in at test','2012-06-27 21:14:54'),
  (148,29,212,'check_in_location','Sadie has Checked in at test','2012-06-27 21:14:54'),
  (149,38,214,'add_pet','Your friend Tiffany Wynn add new Other by name David','2012-06-27 21:22:05'),
  (150,38,214,'check_in_location','David has Checked in at Taco Bell','2012-06-27 21:22:37'),
  (151,29,213,'change_pet_status','David change status for Kylie','2012-06-28 02:45:32'),
  (152,29,212,'change_pet_status','David change status for Sadie','2012-06-28 02:45:41'),
  (153,29,212,'change_pet_status','David change status for Sadie','2012-06-28 02:48:44'),
  (154,74,215,'add_pet','Your friend kkk kkk add new Dog by name test','2012-06-28 05:13:23'),
  (160,35,221,'add_pet','Your friend Vitaly Timofeev add new Dog by name test','2012-06-28 07:31:12'),
  (161,35,221,'change_pet_status','Vitaly change status for test','2012-06-28 07:32:52'),
  (162,76,222,'add_pet','Your friend tag_test test add new Dog by name tag','2012-06-28 13:49:33'),
  (163,29,213,'check_in_location','Kylie has Checked in at David''s House','2012-06-28 15:18:28'),
  (164,29,212,'check_in_location','Sadie has Checked in at David''s House','2012-06-28 15:18:28'),
  (165,77,223,'add_pet','Your friend Peyton Cook add new Dog by name Bell','2012-06-29 02:03:23'),
  (166,77,223,'upload_photo','Peyton add new photo for Bell','2012-06-29 02:06:48'),
  (167,76,224,'add_pet','Your friend tag_test test add new Dog by name test2','2012-06-29 05:48:52'),
  (168,29,212,'check_in_location','Sadie has Checked in at Steadman Veterinary Hospital','2012-06-29 07:19:16'),
  (169,29,213,'check_in_location','Kylie has Checked in at Steadman Veterinary Hospital','2012-06-29 07:19:16'),
  (174,78,226,'add_pet','Your friend Anton Repjov add new Dog by name Sharik','2012-06-29 13:08:09'),
  (175,76,NULL,'lost_pet','tag_test test lost tag!!','2012-06-29 13:41:14'),
  (176,76,NULL,'lost_pet','tag_test test lost tag!!','2012-06-29 13:50:06'),
  (177,76,NULL,'lost_pet','tag_test test lost tag!!','2012-06-29 14:03:35'),
  (178,76,NULL,'find_pet','tag_test test find tag!!','2012-06-29 14:04:10'),
  (179,76,NULL,'find_pet','tag_test test find tag!!','2012-06-29 14:04:56'),
  (180,76,NULL,'lost_pet','tag_test test lost tag!!','2012-06-29 14:20:41'),
  (181,29,212,'check_in_location','Sadie has Checked in at Hillman Veterinary Clinic','2012-06-29 14:41:13'),
  (182,29,213,'check_in_location','Kylie has Checked in at Hillman Veterinary Clinic','2012-06-29 14:41:13'),
  (183,29,NULL,'lost_pet','David Latshaw lost Kylie!!','2012-06-29 14:42:51'),
  (184,29,212,'check_in_location','Sadie has Checked in at Bayview Dog Beach','2012-06-29 19:32:40'),
  (185,29,213,'check_in_location','Kylie has Checked in at Bayview Dog Beach','2012-06-29 19:32:40'),
  (186,29,NULL,'find_pet','David Latshaw find tag!!','2012-06-29 19:36:35'),
  (187,29,212,'change_pet_status','David change status for Sadie','2012-06-29 19:39:10'),
  (188,29,NULL,'lost_pet','David Latshaw lost Kylie!!','2012-06-29 19:39:48'),
  (189,79,227,'add_pet','Your friend Terry Abbott add new Dog by name Parker','2012-06-29 20:02:06'),
  (190,76,NULL,'lost_pet','tag_test test lost test2!!','2012-07-02 06:12:32'),
  (191,76,NULL,'lost_pet','tag_test test lost test2!!','2012-07-02 06:27:10'),
  (192,76,222,'upload_photo','tag_test add new photo for tag','2012-07-02 13:21:33'),
  (193,76,222,'upload_photo','tag_test add new photo for tag','2012-07-02 13:22:22'),
  (194,76,222,'upload_photo','tag_test add new photo for tag','2012-07-02 13:25:31'),
  (195,74,NULL,'lost_pet','kkk kkk lost pet!!','2012-07-03 07:24:14'),
  (201,31,233,'add_pet','Your friend Александр Леонтьев add new Dog by name Barbos','2012-07-03 11:36:34'),
  (202,31,NULL,'lost_pet','Александр Леонтьев lost Barbos!!','2012-07-03 11:39:26'),
  (203,35,NULL,'lost_pet','Vitaly Timofeev lost test!!','2012-07-03 13:31:43'),
  (205,35,NULL,'lost_pet','Vitaly Timofeev lost push!!','2012-07-03 13:37:44'),
  (207,35,NULL,'lost_pet','Vitaly Timofeev lost push 2!!','2012-07-03 13:39:51'),
  (209,35,NULL,'lost_pet','Vitaly Timofeev lost werwe!!','2012-07-03 13:40:56'),
  (210,35,221,'check_in_location','test has Checked in at Test2','2012-07-04 05:27:00'),
  (214,35,221,'check_in_location','test has Checked in at Test2','2012-07-04 05:27:16'),
  (216,31,NULL,'find_pet','Александр Леонтьев find Barbos!!','2012-07-04 06:21:12'),
  (218,35,NULL,'lost_pet','Vitaly Timofeev lost lost!!','2012-07-04 11:37:22'),
  (219,35,NULL,'lost_pet','Vitaly Timofeev lost lost!!','2012-07-04 11:37:57'),
  (221,35,NULL,'lost_pet','Vitaly Timofeev lost uiui!!','2012-07-04 11:38:55'),
  (223,35,NULL,'lost_pet','Vitaly Timofeev lost ghp!!','2012-07-04 11:39:45'),
  (225,35,NULL,'lost_pet','Vitaly Timofeev lost jhjkfd!!','2012-07-04 11:40:20'),
  (227,35,NULL,'lost_pet','Vitaly Timofeev lost hty!!','2012-07-04 11:44:25'),
  (228,35,242,'add_pet','Your friend Vitaly Timofeev add new Dog by name ppp','2012-07-04 11:52:34'),
  (229,35,NULL,'lost_pet','Vitaly Timofeev lost ppp!!','2012-07-04 11:54:36'),
  (230,76,243,'add_pet','Your friend tag_test test add new Dog by name push','2012-07-04 12:37:50'),
  (231,76,NULL,'lost_pet','tag_test test lost push!!','2012-07-04 12:38:05'),
  (232,35,244,'add_pet','Your friend Vitaly Timofeev add new Dog by name kkk','2012-07-04 12:39:19'),
  (233,35,NULL,'lost_pet','Vitaly Timofeev lost kkk!!','2012-07-04 12:39:31'),
  (234,35,245,'add_pet','Your friend Vitaly Timofeev add new Dog by name qweqw','2012-07-04 12:41:26'),
  (235,35,NULL,'lost_pet','Vitaly Timofeev lost qweqw!!','2012-07-04 12:42:19'),
  (236,35,246,'add_pet','Your friend Vitaly Timofeev add new Dog by name fgddfg','2012-07-04 12:43:03'),
  (237,35,NULL,'lost_pet','Vitaly Timofeev lost fgddfg!!','2012-07-04 12:43:22'),
  (238,35,247,'add_pet','Your friend Vitaly Timofeev add new Dog by name werwer','2012-07-04 14:25:25'),
  (239,35,NULL,'lost_pet','Vitaly Timofeev lost werwer!!','2012-07-04 14:25:42'),
  (240,35,248,'add_pet','Your friend Vitaly Timofeev add new Dog by name qwe','2012-07-04 14:27:24'),
  (241,35,NULL,'lost_pet','Vitaly Timofeev lost qwe!!','2012-07-04 14:29:08'),
  (242,29,NULL,'find_pet','David Latshaw find Sadie!!','2012-07-04 14:31:49'),
  (243,29,212,'change_pet_status','David change status for Sadie','2012-07-04 14:58:14'),
  (244,29,NULL,'find_pet','David Latshaw find Barbos!!','2012-07-04 15:01:31'),
  (245,29,213,'check_in_location','Kylie has Checked in at Bayview Park','2012-07-04 15:03:20'),
  (246,29,212,'check_in_location','Sadie has Checked in at Bayview Park','2012-07-04 15:03:20'),
  (247,29,212,'change_pet_status','David change status for Sadie','2012-07-04 15:04:51'),
  (248,29,212,'upload_photo','David add new photo for Sadie','2012-07-04 15:05:40'),
  (249,76,249,'add_pet','Your friend tag_test test add new Dog by name pdf','2012-07-05 11:07:45'),
  (250,29,212,'check_in_location','Sadie has Checked in at Bayview Dog Beach','2012-07-05 12:51:05'),
  (251,29,213,'check_in_location','Kylie has Checked in at Bayview Dog Beach','2012-07-05 12:51:05'),
  (252,76,NULL,'lost_pet','tag_test test lost pdf!!','2012-07-05 14:10:21'),
  (253,76,250,'add_pet','Your friend tag_test test add new Dog by name lll','2012-07-05 14:14:33'),
  (254,76,251,'add_pet','Your friend tag_test test add new Dog by name erdfs','2012-07-05 14:16:59'),
  (255,29,213,'change_pet_status','David change status for Kylie','2012-07-06 00:54:17'),
  (256,29,213,'upload_photo','David add new photo for Kylie','2012-07-06 00:54:50'),
  (257,29,NULL,'find_pet','David Latshaw find pdf!!','2012-07-06 01:44:04'),
  (258,29,252,'add_pet','Your friend David Latshaw add new Reptile by name Lizzy','2012-07-06 01:47:12'),
  (261,78,253,'add_pet','Your friend Anton Repjov add new Dog by name Rob','2012-07-06 06:55:25'),
  (269,76,224,'check_in_location','test2 has Checked in at Test','2012-07-06 08:20:42'),
  (270,76,243,'check_in_location','push has Checked in at Test','2012-07-06 08:20:42'),
  (275,87,257,'add_pet','Your friend hhh hhh add new Dog by name wqe','2012-07-06 11:42:05'),
  (284,35,248,'lost_pet','Vitaly Timofeev lost qwe!!','2012-07-06 12:30:26'),
  (285,35,248,'lost_pet','Vitaly Timofeev lost qwe!!','2012-07-06 12:39:31'),
  (286,35,258,'add_pet','Your friend Vitaly Timofeev add new Dog by name ooo','2012-07-06 12:41:13'),
  (287,35,258,'lost_pet','Vitaly Timofeev lost ooo!!','2012-07-06 12:42:14'),
  (288,35,259,'add_pet','Your friend Vitaly Timofeev add new Dog by name vxcv','2012-07-06 13:05:18'),
  (289,35,259,'lost_pet','Vitaly Timofeev lost vxcv!!','2012-07-06 13:05:49'),
  (290,89,255,'lost_pet','Ivan Sklyarov lost Ad!!','2012-07-06 13:31:22'),
  (291,89,255,'lost_pet','Ivan Sklyarov lost Ad!!','2012-07-06 13:51:42'),
  (292,89,255,'lost_pet','Ivan Sklyarov lost Ad!!','2012-07-06 14:04:31'),
  (293,35,260,'add_pet','Your friend Vitaly Timofeev add new Dog by name qwe','2012-07-06 14:06:15'),
  (294,35,260,'lost_pet','Vitaly Timofeev lost qwe!!','2012-07-06 14:06:41');
COMMIT;

#
# Data for the `friendships` table  (LIMIT 0,500)
#

INSERT INTO `friendships` (`user_id`, `friend_id`, `accepted`) VALUES 
  (23,35,1),
  (29,23,0),
  (29,26,0),
  (29,31,1),
  (29,34,1),
  (29,35,1),
  (29,38,1),
  (29,74,0),
  (29,76,1),
  (29,77,1),
  (29,78,1),
  (29,79,1),
  (29,80,0),
  (29,86,0),
  (31,29,1),
  (31,34,1),
  (31,35,1),
  (31,78,1),
  (34,29,1),
  (34,31,1),
  (35,23,1),
  (35,29,1),
  (35,31,1),
  (35,76,1),
  (38,29,1),
  (76,29,1),
  (76,35,1),
  (76,89,1),
  (77,29,1),
  (77,79,1),
  (78,29,1),
  (78,31,1),
  (79,29,1),
  (79,77,1),
  (89,76,1);
COMMIT;

#
# Data for the `location_categories` table  (LIMIT 0,500)
#

INSERT INTO `location_categories` (`id`, `name`) VALUES 
  (1,'Kennel'),
  (2,'Veterinarian'),
  (3,'Restaurants'),
  (4,'Stores'),
  (5,'Other'),
  (6,'Parks');
COMMIT;

#
# Data for the `locations` table  (LIMIT 0,500)
#

INSERT INTO `locations` (`id`, `name`, `category_id`, `description`, `picture`, `address`, `phone`, `point`, `status`, `isConfirm`) VALUES 
  (2,'Location1',1,'Best place','','Ростов-на-Дону, Ростовская область, Россия','123-123-1231',0x0000000001010000003DFA787D249C4740D076DD3863D84340,1,1),
  (3,'Park',5,'Park','','','123-123-1231',0x000000000101000000A2D6DEC4B19D4740CB7635338C6C4340,1,0),
  (4,'test',3,'test','6b27654941c0f7e950c96db2e53d5ab2.png','',NULL,0x0000000001010000004F17CC81FAA2474097DBA0574B6B4340,1,0),
  (6,'Veterinarian Emergency Referral Center',2,'The Veterinary Emergency Referral Center is a state of the art veterinary hospital providing emergency and referral services 24 hours a day 7 days a week. We pride ourselves on maintaining a highly trained staff of doctors and veterinary technicians. Our entire staff is dedicated to providing the highest quality medical care available for our patients. As always we are a referral only hospital and no general practice services or product are offered. ','','4800 North Davis Highway, Pensacola, Florida 32503','--',0x0000000001010000006CDCAC0B89773E40CC3F9F6D5ACE55C0,1,1),
  (7,'Airport Animal Hospital',2,'','','6209 North 9th Avenue, Pensacola, Florida 32504','--',0x000000000101000000B519A721AA7C3E40AC2EA704C4CC55C0,1,1),
  (9,'Animal Hospital of Pensacola',2,'','','5001 North 12th Avenue, Pensacola, Florida 32504','--',0x0000000001010000009C679192C3783E4028101BD1E2CC55C0,1,1),
  (10,'Banfield',2,'','','6251 North Davis Highway, Pensacola, Florida 32504','--',0x0000000001010000008D9AAF928F7D3E40101FD8F15FCE55C0,1,1),
  (11,'Brentwood Animal Hospital',2,'','','5101 North Palafox Street, Pensacola, Florida 32505','--',0x0000000001010000008F72309B00773E40B81457957DCF55C0,1,1),
  (12,'Cat Clinic of Pensacola',2,'','','2322 East 9 Mile Road, Pensacola, FL','--',0x000000000101000000E383431438883E40B03907CF84CD55C0,1,1),
  (13,'Chemstrand Oaks Veterinary',2,'','','10229 Chemstrand Road, Pensacola, FL','--',0x000000000101000000A26131EA5A8B3E40F4622827DAD055C0,1,1),
  (14,'Cordova Animal Medical Center',2,'','','2433 Langley Avenue, Pensacola, Florida 32504','--',0x000000000101000000BDF84784EB7C3E4040C978EF83CC55C0,1,1),
  (15,'East Hill Animal Hospital',2,'','','805 East Gadsden Street, Pensacola, Florida 32501','--',0x000000000101000000B01985C9F96B3E40CCAF8B474ECD55C0,1,1),
  (16,'Gulf Point Animal Hospital',2,'','','3800 Creighton Road, Pensacola, Florida 32504','--',0x000000000101000000E8F120E28B7F3E4058AC2BB011CB55C0,1,1),
  (17,'Ferry Pass Animal Hospital',2,'','','8065 North 9th Avenue, Pensacola, Florida 32514','--',0x000000000101000000D84BAEBDAA823E40B425AB22DCCB55C0,1,1),
  (18,'Hillman Veterinary Clinic',2,'','','2101 North Palafox Street, Pensacola, Florida 32501','--',0x00000000010100000071AB3132126F3E40D0EB3EB61BCE55C0,1,1),
  (19,'Lost Key Animal Clinic',2,'','','4190 Bauer Road, Pensacola, FL','--',0x000000000101000000302E55698B573E40504A0856D5D955C0,1,1),
  (20,'Megan''s Landing Veterinary',2,'','','10081 West Highway 98, Pensacola, Florida 32506','--',0x000000000101000000F71DC3633F673E40EC707495EED655C0,1,1),
  (21,'Navy Boulevard Animal Hospital',2,'','','3835 West Navy Boulevard, Pensacola, Florida 32507','--',0x000000000101000000F866E5A8ED683E401049F4320AD155C0,1,1),
  (22,'Osceola Pet Care',2,'','','5051 Mobile Highway, Pensacola, Florida 32506','--',0x0000000001010000008442BA8E27723E4068FF4D3970D255C0,1,1),
  (23,'Pensacola Veterinary Hospital',2,'','','804 New Warrington Rd, Pensacola, Florida 32506','--',0x000000000101000000E99C9FE2386C3E404483143C85D155C0,1,1),
  (24,'Pineforest Animal Clinic',2,'','','6860 Pine Forest Road, Pensacola, Florida 32526','--',0x000000000101000000B29476B4BB7B3E4080D82D5D9CD355C0,1,1),
  (25,'Pine Meadow Veterinary Clinic',2,'','','550 West 9 Mile Road, Pensacola, FL','--',0x00000000010100000016B545E39A883E40D88FA8AB16D255C0,1,1),
  (26,'Safe Harbor Animal Hospital',2,'','','820 Creighton Road, Pensacola, Florida 32504','--',0x0000000001010000002FDE8FDB2F7F3E4084328D2617CE55C0,1,1),
  (27,'Scenic Hills Veterinary Hospital',2,'','','1301 East 9 Mile Road, Pensacola, Florida 32514','--',0x0000000001010000005557056A31883E4094287B4B39CF55C0,1,1),
  (28,'Spanish Trail Veterinarian Hospital',2,'','','6801 Spanish Trail, Pensacola, Florida 32504','--',0x000000000101000000C00303081F7E3E40F83FC05AB5CA55C0,1,1),
  (29,'Steadman Veterinary Hospital',2,'','','4230 North Davis Highway, Pensacola, Florida 32503','--',0x00000000010100000084B7AC67AD753E40106036A64CCE55C0,1,1),
  (30,'Warrington Veterinary Clinic',2,'','','7197 West Highway 98, Pensacola, Florida 32506','--',0x000000000101000000CCB22781CD653E40740D33349ED355C0,1,1),
  (31,'Westside Animal Hospital',2,'','','711 West Fairfield Drive, Pensacola, Florida 32506','--',0x000000000101000000CAAE0F46476B3E40C41A2E724FD455C0,1,1),
  (32,'Woodbine Animal Clinic',2,'','','4263 County Road 197A, Pace, Florida 32571','--',0x0000000001010000001EBD8685FF973E40D824E42865CB55C0,1,1),
  (33,'Vvvvvvv',2,'Cccccc','','Malo-Naberezhnaya ulitsa 61, Taganrog','',0x000000000101000000E3361AC05B9C4740C959D8D30E774340,1,0),
  (34,'Test',2,'Ggg','33d7395f9298a318444e280954f6813d.jpg','560 Powell St, San Francisco, 94108','',0x0000000001010000009A779CA223E54240C72B103D299A5EC0,1,0),
  (35,'Test2',3,'Test2','','201 Grant Ave, San Francisco, 94108','',0x000000000101000000A0C03BF9F4E4424054573ECBF3995EC0,1,0),
  (36,'Test Location',5,'Test Description here','','25222 E Welches Rd, Welches, 97067','',0x000000000101000000C4B12E6EA3AB46402716F88A6E7D5EC0,1,0),
  (37,'Bayview Park',6,'We need to add Parks category on iPhone.','06db72f28b13a82376d2c44cd58299b3.jpg','2001–2099 E Lloyd St, Pensacola, 32503','--',0x000000000101000000397EA834626E3E4000C5C89239CC55C0,1,0),
  (38,'Taco Bell',5,'Blah blah blah','1c2f7b9d536e3a9692d82a344abd133f.jpg','110 Gulf Breeze Pkwy, Gulf Breeze, 32561','',0x0000000001010000006B0DA5F6225E3E405E9D6340F6CA55C0,1,0),
  (39,'David''s House',5,'This is a test','','511 W. Blount St.','850-291-3592',0x00000000010100000000000000000000000000000000000000,1,0),
  (40,'Bayview Dog Beach',6,'An awesome beach for dogs to swim and play.','e332fd0c089f1afa7442d3e670d4a91e.jpg','2001–2099 E Lloyd St, Pensacola, 32503','',0x000000000101000000B24AE9995E6E3E40A1A2EA573ACC55C0,1,0),
  (41,'Roger Scott Dog Park',6,'','','2130 Summit Blvd\nPensacola, Florida 32504','--',0x00000000010100000000000000000000000000000000000000,1,0),
  (42,' Gulf Breeze Animal Hospital',2,'http://maps.google.com/local_url?dq=veterinarian&q=http://www.gulfbreezeanimalhospital.com/&oi=miw&sa=X&ct=miw_link&cd=1&cad=homepage,cid:6467319110829896476&ei=wmLvT9msDuOWwQH515iCCQ&s=ANYYN7mNch_nsURki0w6_9dF362rJvG7Dg','','2727 Gulf Breeze Parkway\nGulf Breeze, FL 32563','850-932-6116',0x00000000010100000000000000000000000000000000000000,1,1),
  (43,' Parkway Animal Hospital',2,'','','1196 Gulf Breeze Parkway\nGulf Breeze, FL 32561','850-932-5534',0x00000000010100000000000000000000000000000000000000,1,1),
  (44,'Animal Medical Center',2,'','','3205 Gulf Breeze Parkway\nGulf Breeze, FL 32563','850-932-6085',0x00000000010100000000000000000000000000000000000000,1,0),
  (45,'Animal Medical Center',2,'','','3205 Gulf Breeze Parkway\nGulf Breeze, FL 32563','850-932-6085',0x00000000010100000000000000000000000000000000000000,1,0),
  (46,'St Francis Veterinary Center',2,'','','1856 Cotton Bay Lane\nNavarre, FL 32566','850-936-4446',0x00000000010100000000000000000000000000000000000000,1,0),
  (47,'Soundside Animal Hospital',2,'','','7552 Navarre Pkwy # 3\nNavarre, FL 32566','850-939-6080',0x00000000010100000000000000000000000000000000000000,1,0);
COMMIT;

#
# Data for the `location_checkins` table  (LIMIT 0,500)
#

INSERT INTO `location_checkins` (`location_id`, `pet_id`, `user_id`) VALUES 
  (38,214,38),
  (40,213,29),
  (34,224,76),
  (34,243,76);
COMMIT;

#
# Data for the `location_confirms` table  (LIMIT 0,500)
#

INSERT INTO `location_confirms` (`location_id`, `user_id`) VALUES 
  (4,23),
  (2,23),
  (33,23),
  (34,35),
  (36,29),
  (37,29),
  (3,29),
  (33,29),
  (4,29),
  (38,38),
  (40,29);
COMMIT;

#
# Data for the `pet_finds` table  (LIMIT 0,500)
#

INSERT INTO `pet_finds` (`id`, `pet_id`, `user_id`, `address`, `point`) VALUES 
  (1,222,76,'300–336 S Tarragona St, Pensacola, Florida, United States',0x000000000101000000416150A6D1683E40F3936A9F8ECD55C0),
  (2,233,31,'301–337 S Tarragona St, Pensacola, Florida, United States',0x00000000010100000093020B60CA683E409C35785F95CD55C0),
  (3,212,29,'105–185 E Government St, Pensacola, Florida, United States',0x0000000001010000000A849D62D5683E40B2F50CE198CD55C0),
  (4,249,29,'700 W Brainerd St, Pensacola, Florida, United States',0x0000000001010000004A97FE25A96C3E401EC022BF7ECE55C0);
COMMIT;

#
# Data for the `pet_losts` table  (LIMIT 0,500)
#

INSERT INTO `pet_losts` (`pet_id`, `last_seen`, `point`, `pdf`) VALUES 
  (222,'taganrog',0x000000000101000000AAD4EC81569C4740BF49D3A068744340,NULL),
  (224,'taganrog',0x000000000101000000AAD4EC81569C4740BF49D3A068744340,NULL),
  (207,'301 Powell St, San Francisco, California, United States',0x000000000101000000FB05BB61DBE44240F7C95180289A5EC0,NULL),
  (233,'Taganrog, Socialisticheskaya 7',0x000000000101000000DC2A8881AEE542404D49D6E1E8995EC0,NULL),
  (221,'Rostov',0x00000000010100000063B83A00E28C47407442E8A04BB64340,NULL),
  (242,'Rostov',0x00000000010100000063B83A00E28C47407442E8A04BB64340,NULL),
  (243,'Taganrog',0x000000000101000000AAD4EC81569C4740BF49D3A068744340,NULL),
  (244,'Taganrog',0x000000000101000000AAD4EC81569C4740BF49D3A068744340,NULL),
  (245,'Taganrog',0x00000000010100000068CA4E3FA89B47400987DEE2E1754340,NULL),
  (246,'Rostov',0x00000000010100000063B83A00E28C47407442E8A04BB64340,NULL),
  (247,'Taganrog',0x000000000101000000AAD4EC81569C4740BF49D3A068744340,NULL),
  (249,'139 E. Government St. Pensacola, FL 32502',0x0000000001010000007651F4C0C7683E408578245E9ECD55C0,'c90b9e4cf62d33af6d2f2a742fed8aef.jpg'),
  (256,'Таганрог, Ростовская область, Россия',0x000000000101000000464E96AA8B9B4740D076853E4A714340,'qI9YrbMn.pdf'),
  (248,'Taganrog, Rostov Oblast, Russia',0x00000000010100000015283CBC859C474030E2B8425A754340,'hM2ieG2Y.pdf'),
  (258,'taganrog',0x000000000101000000F0517FBDC29C47405DDF878384744340,'TUL7GjYV.pdf'),
  (259,'Taganrog',0x0000000001010000006DE2E47E879C47401557957D57744340,'a9t6l6j6.pdf'),
  (255,'Неклиновский, Ростовская область, Россия',0x000000000101000000464E96AA8B9B4740D076853E6C6D4340,'A0pJkOVP.pdf'),
  (260,'Taganrog',0x000000000101000000CA37DBDC989C4740944A7842AF754340,'IFLoiVO6.pdf');
COMMIT;

#
# Data for the `pet_photos` table  (LIMIT 0,500)
#

INSERT INTO `pet_photos` (`id`, `pet_id`, `name`, `date_created`) VALUES 
  (20,212,'2541a47710e9c1314839a2f212cf4be0.jpg','2012-06-27 14:55:38'),
  (21,213,'0a1a3c2b0ce77450862b936c3a12d6e1.jpg','2012-06-27 15:04:36'),
  (22,213,'76156b458ffd00dc8404416567f1fbbd.jpg','2012-06-27 21:11:50'),
  (23,223,'5eb646b8bfceac9e664dac2a28444710.jpg','2012-06-29 02:06:48'),
  (26,222,'4ea727832a51915e3b2cea3e1c177c44.jpg','2012-07-02 13:21:33'),
  (27,222,'0171ca6c167b11ad8ec215c150d6cc26.jpg','2012-07-02 13:22:22'),
  (28,222,'9d1204ee126a98ec9ea0d147eb394659.jpg','2012-07-02 13:25:31'),
  (29,212,'689d057df2c2793af3390b61c73746db.jpg','2012-07-04 15:05:40'),
  (30,213,'03d126b586208554616f519ee74bb578.jpg','2012-07-06 00:54:50');
COMMIT;

#
# Data for the `pet_tags` table  (LIMIT 0,500)
#

INSERT INTO `pet_tags` (`pet_id`, `stripe_token`, `qrcode`) VALUES 
  (222,'ch_zmxFU80E8L3wk7','6962fba367da2665ac3a114e4bd26f4b.png'),
  (207,'ch_CJO0aXeNinB4OU','8ca2dbce92cad3a1c03af7e6629ae562.png'),
  (215,'ch_V7kPn9SFRozQuN',NULL),
  (233,'ch_kdRIdeMSVkzqon','9b7b9ff52273ddf82324bc2957f60aa2.png'),
  (224,'ch_sLbatO9ozZWbln',NULL);
COMMIT;

#
# Data for the `roles` table  (LIMIT 0,500)
#

INSERT INTO `roles` (`id`, `name`, `description`) VALUES 
  (1,'login','Login privileges, granted after account confirmation'),
  (2,'admin','Administrative user, has access to everything.');
COMMIT;

#
# Data for the `roles_users` table  (LIMIT 0,500)
#

INSERT INTO `roles_users` (`user_id`, `role_id`) VALUES 
  (23,1),
  (23,2),
  (26,1),
  (26,2),
  (29,1),
  (29,2),
  (31,1),
  (31,2),
  (34,1),
  (35,1),
  (35,2),
  (38,1),
  (74,1),
  (76,1),
  (77,1),
  (77,2),
  (78,1),
  (79,1),
  (79,2),
  (80,1),
  (86,1),
  (87,1),
  (89,1);
COMMIT;

