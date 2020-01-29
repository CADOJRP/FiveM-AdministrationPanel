-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: orbithosting.uk
-- Generation Time: Jan 21, 2020 at 10:08 PM
-- Server version: 10.2.29-MariaDB-log
-- PHP Version: 7.1.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `staffpanel-3132337c41`
--

-- --------------------------------------------------------

--
-- Table structure for table `bans`
--

CREATE TABLE `bans` (
  `ID` int(255) NOT NULL,
  `name` varchar(1024) NOT NULL,
  `identifier` varchar(1024) NOT NULL,
  `reason` varchar(1024) NOT NULL,
  `ban_issued` varchar(1024) NOT NULL,
  `banned_until` varchar(1024) NOT NULL,
  `staff_name` varchar(1024) NOT NULL,
  `staff_steamid` varchar(1024) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `commend`
--

CREATE TABLE `commend` (
  `ID` int(255) NOT NULL,
  `license` varchar(255) NOT NULL,
  `reason` varchar(1024) NOT NULL,
  `staff_name` varchar(255) NOT NULL,
  `staff_steamid` varchar(255) NOT NULL,
  `time` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

CREATE TABLE `config` (
  `ID` int(255) NOT NULL,
  `community_name` varchar(255) NOT NULL DEFAULT 'Community Name',
  `discord_webhook` varchar(1024) DEFAULT NULL,
  `joinmessages` enum('true','false') NOT NULL DEFAULT 'false',
  `chatcommands` enum('true','false') NOT NULL DEFAULT 'true',
  `checktimeout` int(255) NOT NULL DEFAULT 15,
  `trustscore` int(255) NOT NULL DEFAULT 75,
  `tswarn` int(255) NOT NULL DEFAULT 3,
  `tskick` int(255) NOT NULL DEFAULT 6,
  `tsban` int(255) NOT NULL DEFAULT 10,
  `tscommend` int(255) NOT NULL DEFAULT 2,
  `tstime` int(255) NOT NULL DEFAULT 1,
  `recent_time` int(255) NOT NULL DEFAULT 10,
  `permissions` varchar(20480) NOT NULL,
  `serveractions` varchar(20480) NOT NULL,
  `debug` enum('false','true') NOT NULL DEFAULT 'false'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `kicks`
--

CREATE TABLE `kicks` (
  `ID` int(255) NOT NULL,
  `license` varchar(1024) NOT NULL,
  `reason` varchar(1024) NOT NULL,
  `staff_name` varchar(255) NOT NULL,
  `staff_steamid` varchar(255) NOT NULL,
  `time` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `players`
--

CREATE TABLE `players` (
  `ID` int(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `license` varchar(255) NOT NULL,
  `steam` varchar(255) NOT NULL,
  `playtime` int(255) NOT NULL DEFAULT 0,
  `firstjoined` varchar(255) NOT NULL,
  `lastplayed` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `servers`
--

CREATE TABLE `servers` (
  `ID` int(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `connection` varchar(255) NOT NULL,
  `rcon` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `ID` int(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `steamid` varchar(255) NOT NULL,
  `rank` varchar(255) NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `warnings`
--

CREATE TABLE `warnings` (
  `ID` int(255) NOT NULL,
  `license` varchar(1024) NOT NULL,
  `reason` varchar(1024) NOT NULL,
  `staff_name` varchar(255) NOT NULL,
  `staff_steamid` varchar(255) NOT NULL,
  `time` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bans`
--
ALTER TABLE `bans`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `commend`
--
ALTER TABLE `commend`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `config`
--
ALTER TABLE `config`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `kicks`
--
ALTER TABLE `kicks`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `players`
--
ALTER TABLE `players`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `license` (`license`);

--
-- Indexes for table `servers`
--
ALTER TABLE `servers`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `steamid` (`steamid`);
ALTER TABLE `users` ADD FULLTEXT KEY `rank` (`rank`);

--
-- Indexes for table `warnings`
--
ALTER TABLE `warnings`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bans`
--
ALTER TABLE `bans`
  MODIFY `ID` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `commend`
--
ALTER TABLE `commend`
  MODIFY `ID` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `config`
--
ALTER TABLE `config`
  MODIFY `ID` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kicks`
--
ALTER TABLE `kicks`
  MODIFY `ID` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `players`
--
ALTER TABLE `players`
  MODIFY `ID` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `warnings`
--
ALTER TABLE `warnings`
  MODIFY `ID` int(255) NOT NULL AUTO_INCREMENT;
COMMIT;

INSERT INTO `config` (`ID`, `community_name`, `discord_webhook`, `joinmessages`, `chatcommands`, `checktimeout`, `trustscore`, `tswarn`, `tskick`, `tsban`, `tscommend`, `tstime`, `recent_time`, `permissions`, `serveractions`, `debug`) VALUES
(1, 'change Me', '', 'false', 'true', 5, 75, 3, 6, 10, 2, 1, 10, 'O:8:\"stdClass\":6:{s:5:\"owner\";a:9:{i:0;s:7:\"commend\";i:1;s:4:\"warn\";i:2;s:4:\"kick\";i:3;s:3:\"ban\";i:4;s:5:\"unban\";i:5;s:9:\"editstaff\";i:6;s:11:\"editservers\";i:7;s:9:\"editpanel\";i:8;s:9:\"delrecord\";}s:16:\"communitymanager\";a:7:{i:0;s:7:\"commend\";i:1;s:4:\"warn\";i:2;s:4:\"kick\";i:3;s:3:\"ban\";i:4;s:5:\"unban\";i:5;s:9:\"editstaff\";i:6;s:9:\"delrecord\";}s:11:\"senioradmin\";a:5:{i:0;s:7:\"commend\";i:1;s:4:\"warn\";i:2;s:4:\"kick\";i:3;s:3:\"ban\";i:4;s:5:\"unban\";}s:5:\"admin\";a:4:{i:0;s:7:\"commend\";i:1;s:4:\"warn\";i:2;s:4:\"kick\";i:3;s:3:\"ban\";}s:9:\"moderator\";a:4:{i:0;s:7:\"commend\";i:1;s:4:\"warn\";i:2;s:4:\"kick\";i:3;s:3:\"ban\";}s:8:\"trialmod\";a:3:{i:0;s:7:\"commend\";i:1;s:4:\"warn\";i:2;s:4:\"kick\";}}', 'O:8:\"stdClass\":1:{s:18:\"108.61.69.48:30120\";O:8:\"stdClass\":4:{s:12:\"kickforstaff\";O:8:\"stdClass\":4:{s:6:\"action\";s:12:\"kickforstaff\";s:5:\"input\";s:0:\"\";s:10:\"buttonname\";s:14:\"Kick For Staff\";s:11:\"buttonstyle\";s:11:\"btn-warning\";}s:10:\"aop-blaine\";O:8:\"stdClass\":4:{s:6:\"action\";s:7:\"command\";s:5:\"input\";s:17:\"aop Blaine County\";s:10:\"buttonname\";s:10:\"AOP Blaine\";s:11:\"buttonstyle\";s:11:\"btn-success\";}s:8:\"aop-city\";O:8:\"stdClass\":4:{s:6:\"action\";s:7:\"command\";s:5:\"input\";s:14:\"aop Los Santos\";s:10:\"buttonname\";s:8:\"AOP City\";s:11:\"buttonstyle\";s:11:\"btn-success\";}s:10:\"aop-paleto\";O:8:\"stdClass\":4:{s:6:\"action\";s:7:\"command\";s:5:\"input\";s:14:\"aop Paleto Bay\";s:10:\"buttonname\";s:10:\"AOP Paleto\";s:11:\"buttonstyle\";s:11:\"btn-success\";}}}', 'false');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
