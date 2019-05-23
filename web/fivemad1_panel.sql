
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `bans`
--

DROP TABLE IF EXISTS `bans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bans` (
  `ID` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(1024) NOT NULL,
  `identifier` varchar(1024) NOT NULL,
  `reason` varchar(1024) NOT NULL,
  `ban_issued` varchar(1024) NOT NULL,
  `banned_until` varchar(1024) NOT NULL,
  `staff_name` varchar(1024) NOT NULL,
  `staff_steamid` varchar(1024) NOT NULL,
  `community` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=26266 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;



--
-- Table structure for table `commend`
--

DROP TABLE IF EXISTS `commend`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `commend` (
  `ID` int(255) NOT NULL AUTO_INCREMENT,
  `license` varchar(255) NOT NULL,
  `reason` varchar(1024) NOT NULL,
  `staff_name` varchar(255) NOT NULL,
  `staff_steamid` varchar(255) NOT NULL,
  `time` varchar(255) NOT NULL,
  `community` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=5232 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;



--
-- Table structure for table `communities`
--

DROP TABLE IF EXISTS `communities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `communities` (
  `ID` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL DEFAULT '',
  `time` int(11) NOT NULL,
  `uniqueid` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=1799 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;



--
-- Table structure for table `config`
--

DROP TABLE IF EXISTS `config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `config` (
  `ID` int(255) NOT NULL AUTO_INCREMENT,
  `community_name` varchar(255) NOT NULL DEFAULT 'Community Name',
  `discord_webhook` varchar(1024) DEFAULT NULL,
  `joinmessages` enum('true','false') NOT NULL DEFAULT 'false',
  `chatcommands` enum('true','false') NOT NULL DEFAULT 'true',
  `checktimeout` int(255) NOT NULL DEFAULT '15',
  `trustscore` int(255) NOT NULL DEFAULT '75',
  `tswarn` int(255) NOT NULL DEFAULT '3',
  `tskick` int(255) NOT NULL DEFAULT '6',
  `tsban` int(255) NOT NULL DEFAULT '10',
  `tscommend` int(255) NOT NULL DEFAULT '2',
  `tstime` int(255) NOT NULL DEFAULT '1',
  `recent_time` int(255) NOT NULL DEFAULT '10',
  `permissions` varchar(20480) NOT NULL,
  `serveractions` varchar(20480) NOT NULL,
  `themecss` varchar(20000) NOT NULL DEFAULT '',
  `debug` enum('false','true') NOT NULL DEFAULT 'false',
  `community` varchar(255) NOT NULL,
  `plugin_extendeduserinfo` tinyint(1) NOT NULL DEFAULT '0',
  `plugin_globaluserinfo` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=1796 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;



--
-- Table structure for table `kicks`
--

DROP TABLE IF EXISTS `kicks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kicks` (
  `ID` int(255) NOT NULL AUTO_INCREMENT,
  `license` varchar(1024) NOT NULL,
  `reason` varchar(1024) NOT NULL,
  `staff_name` varchar(255) NOT NULL,
  `staff_steamid` varchar(255) NOT NULL,
  `time` varchar(255) NOT NULL,
  `community` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=60358 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;



--
-- Table structure for table `notes`
--

DROP TABLE IF EXISTS `notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notes` (
  `ID` int(255) NOT NULL AUTO_INCREMENT,
  `license` varchar(255) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `staff_name` varchar(255) NOT NULL,
  `staff_steamid` varchar(255) NOT NULL,
  `time` varchar(255) NOT NULL,
  `community` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=1533 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `players`
--

DROP TABLE IF EXISTS `players`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `players` (
  `ID` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `license` varchar(255) NOT NULL,
  `steam` varchar(255) NOT NULL,
  `discord` varchar(255) DEFAULT NULL,
  `playtime` int(255) NOT NULL DEFAULT '0',
  `firstjoined` varchar(255) NOT NULL,
  `lastplayed` varchar(255) NOT NULL,
  `community` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `license` (`license`,`community`)
) ENGINE=MyISAM AUTO_INCREMENT=42669341 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;



--
-- Table structure for table `servers`
--

DROP TABLE IF EXISTS `servers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `servers` (
  `ID` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `connection` varchar(255) NOT NULL,
  `rcon` varchar(255) NOT NULL,
  `players` int(255) NOT NULL DEFAULT '0',
  `community` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=1772 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;



--
-- Table structure for table `support_comments`
--

DROP TABLE IF EXISTS `support_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `support_comments` (
  `ID` int(255) NOT NULL AUTO_INCREMENT,
  `message` varchar(2048) NOT NULL,
  `ticketid` varchar(255) NOT NULL,
  `commentid` varchar(255) NOT NULL,
  `steamid` varchar(255) NOT NULL,
  `time` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=56 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;



--
-- Table structure for table `support_tickets`
--

DROP TABLE IF EXISTS `support_tickets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `support_tickets` (
  `ID` int(255) NOT NULL AUTO_INCREMENT,
  `title` varchar(1024) NOT NULL,
  `message` varchar(2048) NOT NULL,
  `ticketid` varchar(255) NOT NULL,
  `steamid` varchar(255) NOT NULL,
  `status` enum('open','in-progress','pending','closed') NOT NULL DEFAULT 'open',
  `time` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=71 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `ID` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `steamid` varchar(255) NOT NULL,
  `rank` varchar(255) NOT NULL DEFAULT 'user',
  `community` varchar(255) NOT NULL DEFAULT '',
  `staff` tinyint(1) NOT NULL DEFAULT '0',
  `beta` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `steamid` (`steamid`),
  FULLTEXT KEY `rank` (`rank`)
) ENGINE=MyISAM AUTO_INCREMENT=33645 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `warnings`
--

DROP TABLE IF EXISTS `warnings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `warnings` (
  `ID` int(255) NOT NULL AUTO_INCREMENT,
  `license` varchar(1024) NOT NULL,
  `reason` varchar(1024) NOT NULL,
  `staff_name` varchar(255) NOT NULL,
  `staff_steamid` varchar(255) NOT NULL,
  `time` varchar(255) NOT NULL,
  `community` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=32444 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;



/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-04-22 18:23:04

ALTER TABLE `communities` 
    ADD `active` INT(8) NOT NULL DEFAULT '0' AFTER `uniqueid`;

ALTER TABLE `bans` 
    ADD `steam` VARCHAR(255) NULL AFTER `identifier`, 
    ADD `discord` VARCHAR(255) NULL AFTER `steam`, 
    ADD `xbl` VARCHAR(255) NULL AFTER `discord`, 
    ADD `ip` VARCHAR(255) NULL AFTER `xbl`, 
    ADD `live` VARCHAR(255) NULL AFTER `ip`;

ALTER TABLE `communities` 
    ADD `email` VARCHAR(255) NULL AFTER `uniqueid`;
