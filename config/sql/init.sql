-- MySQL dump 11.13  Distrib 5.1.58, for apple-darwin10.3.0 (i386)
--
-- Host: localhost    Database: sgol
-- ------------------------------------------------------
-- Server version	5.1.58

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES latin1 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `apps`
--

DROP TABLE IF EXISTS `apps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `apps` (
  `type` varchar(256) NOT NULL DEFAULT '',
  `secret` varchar(256) NOT NULL DEFAULT '',
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `apps`
--

LOCK TABLES `apps` WRITE;
/*!40000 ALTER TABLE `apps` DISABLE KEYS */;
INSERT INTO `apps` VALUES ('web','123456',1),('ios','123456',2),('android','123456',3);
/*!40000 ALTER TABLE `apps` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `game_assignments`
--

DROP TABLE IF EXISTS `game_assignments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `game_assignments` (
  `game_id` int(10) unsigned NOT NULL DEFAULT '0',
  `role_id` int(10) unsigned NOT NULL DEFAULT '0',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `killed_by` varchar(256) NOT NULL DEFAULT '',
  `credits` int(10) unsigned NOT NULL DEFAULT '0',
  `seat` varchar(256) NOT NULL DEFAULT '',
  `created` int(10) unsigned NOT NULL DEFAULT '0',
  `updated` int(10) unsigned NOT NULL DEFAULT '0',
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `game_assignments`
--

LOCK TABLES `game_assignments` WRITE;
/*!40000 ALTER TABLE `game_assignments` DISABLE KEYS */;
/*!40000 ALTER TABLE `game_assignments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `game_type_roles`
--

DROP TABLE IF EXISTS `game_type_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `game_type_roles` (
  `game_type_id` int(10) unsigned NOT NULL DEFAULT '0',
  `role_id` int(10) unsigned NOT NULL DEFAULT '0',
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=60 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `game_type_roles`
--

LOCK TABLES `game_type_roles` WRITE;
/*!40000 ALTER TABLE `game_type_roles` DISABLE KEYS */;
INSERT INTO `game_type_roles` VALUES (1,1,1),(1,2,2),(1,3,3),(1,4,4),(1,4,5),(2,1,6),(2,2,7),(2,3,8),(2,4,9),(2,4,10),(2,4,11),(3,1,12),(3,2,13),(3,3,14),(3,3,15),(3,4,16),(3,4,17),(4,1,18),(4,2,19),(4,2,20),(4,3,21),(4,4,22),(4,4,23),(4,4,24),(5,1,25),(5,2,26),(5,2,27),(5,3,28),(5,4,29),(5,4,30),(5,4,31),(5,4,32),(6,1,33),(6,2,34),(6,2,35),(6,3,36),(6,3,37),(6,4,38),(6,4,39),(6,4,40),(7,1,41),(7,2,42),(7,2,43),(7,2,44),(7,3,45),(7,4,46),(7,4,47),(7,4,48),(7,4,49),(8,1,50),(8,2,51),(8,2,52),(8,2,53),(8,3,54),(8,3,55),(8,4,56),(8,4,57),(8,4,58),(8,4,59);
/*!40000 ALTER TABLE `game_type_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `game_types`
--

DROP TABLE IF EXISTS `game_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `game_types` (
  `name` varchar(256) NOT NULL DEFAULT '',
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `game_types`
--

LOCK TABLES `game_types` WRITE;
/*!40000 ALTER TABLE `game_types` DISABLE KEYS */;
INSERT INTO `game_types` VALUES ('5人场',1),('6人场',2),('6人场(双内奸)',3),('7人场',4),('8人场',5),('8人场(双内奸)',6),('9人场',7),('10人场',8);
/*!40000 ALTER TABLE `game_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `games`
--

DROP TABLE IF EXISTS `games`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `games` (
  `game_type_id` int(10) unsigned NOT NULL DEFAULT '0',
  `winner_type_id` int(10) unsigned NOT NULL DEFAULT '0',
  `owner_user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `created` int(10) unsigned NOT NULL DEFAULT '0',
  `updated` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `games`
--

LOCK TABLES `games` WRITE;
/*!40000 ALTER TABLE `games` DISABLE KEYS */;
INSERT INTO `games` VALUES (2,0,1,1344588863,1344588863,1,1),(2,0,1,1344588904,1344588904,1,2),(2,0,1,1344588907,1344588907,1,3);
/*!40000 ALTER TABLE `games` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `name` varchar(256) NOT NULL DEFAULT '',
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES ('主公',1),('忠臣',2),('内奸',3),('反贼',4);
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tokens`
--

DROP TABLE IF EXISTS `tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tokens` (
  `app_id` varchar(32) NOT NULL DEFAULT '',
  `token` varchar(256) NOT NULL DEFAULT '',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `created` int(10) unsigned NOT NULL DEFAULT '0',
  `updated` int(10) unsigned NOT NULL DEFAULT '0',
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tokens`
--

LOCK TABLES `tokens` WRITE;
/*!40000 ALTER TABLE `tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `username` varchar(32) NOT NULL DEFAULT '',
  `password` varchar(32) NOT NULL DEFAULT '',
  `first_name` varchar(32) NOT NULL DEFAULT '',
  `last_name` varchar(32) NOT NULL DEFAULT '',
  `email` varchar(32) NOT NULL DEFAULT '',
  `credits` int(10) unsigned NOT NULL DEFAULT '0',
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES ('jeffery','202cb962ac59075b964b07152d234b70','Jeffery','You','i@youjf.com',0,1),('brent','fe01ce2a7fbac8fafaed7c982a04e229','Brent','Wang','brent.wang@modolabs.com',0,2),('edward','fe01ce2a7fbac8fafaed7c982a04e229','Edward','Liu','edward.liu@modolabs.com',0,3),('newstar','fe01ce2a7fbac8fafaed7c982a04e229','Newstar','Liu','newstar.liu@modolabs.com',0,4),('wolf','fe01ce2a7fbac8fafaed7c982a04e229','Wolf','Wang','yu2.wang@symbio.com',0,5),('lay','fe01ce2a7fbac8fafaed7c982a04e229','Lay','Xiao','lei2.xiao@symbio.com',0,6),('kappa','fe01ce2a7fbac8fafaed7c982a04e229','Kappa','Huang','zhibin.huang@symbio.com',0,7),('allen','fe01ce2a7fbac8fafaed7c982a04e229','Allen','Luo','liangchao.luo@symbio.com',0,8),('xiemin','fe01ce2a7fbac8fafaed7c982a04e229','Min','Xie','',0,9),('weiwei','fe01ce2a7fbac8fafaed7c982a04e229','Weiwei','Wang','',0,10),('guest1','fe01ce2a7fbac8fafaed7c982a04e229','Guest','Number1','',0,11),('guest2','fe01ce2a7fbac8fafaed7c982a04e229','Guest','Number2','',0,12);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2012-08-23 17:44:36

