/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-11.4.9-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: marek_ba1_app
-- ------------------------------------------------------
-- Server version	11.4.9-MariaDB-ubu2204

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `blog`
--

DROP TABLE IF EXISTS `blog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `blog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `topic_id` int(11) NOT NULL,
  `content` longtext NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `public` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_C0155143989D9B62` (`slug`),
  KEY `IDX_C01551431F55203D` (`topic_id`),
  CONSTRAINT `FK_C01551431F55203D` FOREIGN KEY (`topic_id`) REFERENCES `topic` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blog`
--

LOCK TABLES `blog` WRITE;
/*!40000 ALTER TABLE `blog` DISABLE KEYS */;
INSERT INTO `blog` VALUES
(1,2,'Poniżej jest moje zdjęcie \r\n{{img:1}}\r\nTo jest zwykły tekst.\r\nPoniżej jest kod php\r\n[code]<?php echo \"Hello World!\"; ?>[/code]','Wzór','wzor',0);
/*!40000 ALTER TABLE `blog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bot_ip`
--

DROP TABLE IF EXISTS `bot_ip`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `bot_ip` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bot_ip`
--

LOCK TABLES `bot_ip` WRITE;
/*!40000 ALTER TABLE `bot_ip` DISABLE KEYS */;
INSERT INTO `bot_ip` VALUES
(1,'220.181.51.0'),
(2,'206.168.34.0'),
(3,'185.177.72.0'),
(4,'194.163.152.0'),
(5,'116.179.33.0'),
(6,'199.45.155.0'),
(7,'162.142.125.0'),
(8,'167.94.138.0'),
(9,'196.251.70.0'),
(10,'172.189.56.0'),
(11,'141.98.11.0'),
(12,'138.246.253.0'),
(13,'31.56.48.0'),
(14,'18.215.189.0'),
(15,'93.123.109.0'),
(16,'95.168.190.0'),
(17,'176.65.148.0'),
(18,'185.54.231.0'),
(19,'52.169.148.0'),
(20,'52.169.206.0'),
(21,'52.169.13.0'),
(22,'52.231.92.0'),
(23,'52.231.97.0'),
(24,'194.26.192.0'),
(25,'87.106.162.0'),
(26,'169.150.203.0'),
(27,'20.41.84.0'),
(28,'185.207.66.0'),
(29,'164.92.147.0'),
(30,'149.56.150.0'),
(31,'48.210.9.0'),
(32,'40.113.19.0'),
(33,'138.199.35.0'),
(34,'3.99.190.0'),
(35,'3.65.187.0'),
(36,'172.192.16.0'),
(37,'35.183.127.0'),
(38,'172.190.142.0'),
(39,'172.192.74.0'),
(40,'144.48.39.0'),
(41,'45.139.104.0'),
(42,'142.93.143.0'),
(43,'37.131.206.0'),
(44,'188.32.236.0'),
(45,'74.176.189.0'),
(46,'48.210.224.0'),
(47,'45.149.173.0'),
(48,'3.95.181.0'),
(49,'34.1.19.0'),
(50,'35.243.136.0'),
(51,'198.235.24.0'),
(52,'34.26.188.0'),
(53,'34.1.16.0'),
(54,'4.217.189.0'),
(55,'52.169.180.0'),
(56,'85.192.59.0'),
(57,'23.102.70.0'),
(58,'45.141.215.0'),
(59,'4.217.221.0'),
(60,'13.79.87.0'),
(61,'48.218.207.0'),
(62,'104.248.156.0'),
(63,'88.86.212.0'),
(64,'52.178.223.0'),
(65,'91.224.92.0'),
(66,'4.194.8.0'),
(67,'13.70.62.0'),
(68,'203.159.81.0'),
(69,'45.94.31.0'),
(70,'185.207.251.0');
/*!40000 ALTER TABLE `bot_ip` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctrine_migration_versions`
--

LOCK TABLES `doctrine_migration_versions` WRITE;
/*!40000 ALTER TABLE `doctrine_migration_versions` DISABLE KEYS */;
INSERT INTO `doctrine_migration_versions` VALUES
('DoctrineMigrations\\Version20250707165812','2025-07-07 19:01:26',22),
('DoctrineMigrations\\Version20250714153717','2025-07-27 19:08:35',138),
('DoctrineMigrations\\Version20250715200705','2025-07-27 19:08:35',88),
('DoctrineMigrations\\Version20250716201147','2025-07-27 19:08:35',94),
('DoctrineMigrations\\Version20250727172341','2025-07-27 19:08:35',76),
('DoctrineMigrations\\Version20250803162304','2025-08-09 14:54:56',33),
('DoctrineMigrations\\Version20250809141805','2025-08-09 14:54:56',8);
/*!40000 ALTER TABLE `doctrine_migration_versions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `image`
--

DROP TABLE IF EXISTS `image`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `blog_id` int(11) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `position` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_C53D045FDAE07E97` (`blog_id`),
  CONSTRAINT `FK_C53D045FDAE07E97` FOREIGN KEY (`blog_id`) REFERENCES `blog` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `image`
--

LOCK TABLES `image` WRITE;
/*!40000 ALTER TABLE `image` DISABLE KEYS */;
INSERT INTO `image` VALUES
(3,1,'Marek-68a0c524c95e2.jpg',1);
/*!40000 ALTER TABLE `image` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `topic`
--

DROP TABLE IF EXISTS `topic`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `topic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `public` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_9D40DE1B989D9B62` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `topic`
--

LOCK TABLES `topic` WRITE;
/*!40000 ALTER TABLE `topic` DISABLE KEYS */;
INSERT INTO `topic` VALUES
(2,'Wzór','wzor',0);
/*!40000 ALTER TABLE `topic` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(180) NOT NULL,
  `roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`roles`)),
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_IDENTIFIER_EMAIL` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES
(1,'marek-bartocha@hotmail.com','[\"ROLE_ADMIN\"]','$2y$13$TDLxt8.NDoRNiILFy4eX7uO3LkQh2IdO.qykePRjWTCTW.FG0nvfS');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'marek_ba1_app'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2026-01-17 19:01:59
