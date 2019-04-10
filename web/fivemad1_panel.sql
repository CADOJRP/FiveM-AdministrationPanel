-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 23, 2019 at 07:25 PM
-- Server version: 10.1.37-MariaDB
-- PHP Version: 7.3.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fivemad1_panel`
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
  `staff_steamid` varchar(1024) NOT NULL,
  `community` varchar(255) NOT NULL
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
  `time` varchar(255) NOT NULL,
  `community` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `communities`
--

CREATE TABLE `communities` (
  `ID` int(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `uniqueid` varchar(255) NOT NULL
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
  `plugin_globaluserinfo` tinyint(1) NOT NULL DEFAULT '0'
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
  `time` varchar(255) NOT NULL,
  `community` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE `notes` (
  `ID` int(255) NOT NULL,
  `license` varchar(255) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `staff_name` varchar(255) NOT NULL,
  `staff_steamid` varchar(255) NOT NULL,
  `time` varchar(255) NOT NULL,
  `community` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `players`
--

CREATE TABLE `players` (
  `ID` int(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `license` varchar(255) NOT NULL,
  `steam` varchar(255) NOT NULL,
  `discord` varchar(255) DEFAULT NULL,
  `playtime` int(255) NOT NULL DEFAULT '1',
  `firstjoined` varchar(255) NOT NULL,
  `lastplayed` varchar(255) NOT NULL,
  `community` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `servers`
--

CREATE TABLE `servers` (
  `ID` int(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `connection` varchar(255) NOT NULL,
  `rcon` varchar(255) NOT NULL,
  `community` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `support_comments`
--

CREATE TABLE `support_comments` (
  `ID` int(255) NOT NULL,
  `message` varchar(2048) NOT NULL,
  `ticketid` varchar(255) NOT NULL,
  `commentid` varchar(255) NOT NULL,
  `steamid` varchar(255) NOT NULL,
  `time` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `support_tickets`
--

CREATE TABLE `support_tickets` (
  `ID` int(255) NOT NULL,
  `title` varchar(1024) NOT NULL,
  `message` varchar(2048) NOT NULL,
  `ticketid` varchar(255) NOT NULL,
  `steamid` varchar(255) NOT NULL,
  `status` enum('open','in-progress','pending','closed') NOT NULL DEFAULT 'open',
  `time` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `ID` int(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `steamid` varchar(255) NOT NULL,
  `rank` varchar(255) NOT NULL DEFAULT 'user',
  `community` varchar(255) NOT NULL DEFAULT '',
  `staff` tinyint(1) NOT NULL DEFAULT '0',
  `beta` tinyint(1) NOT NULL DEFAULT '0'
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
  `time` varchar(255) NOT NULL,
  `community` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for warnings table
--
ALTER TABLE `warnings`
  ADD PRIMARY KEY (`ID`),
  MODIFY `ID` int(255) NOT NULL AUTO_INCREMENT;
  
--
-- Indexes for bans table
--
ALTER TABLE `bans`
  ADD PRIMARY KEY (`ID`),
  MODIFY `ID` int(255) NOT NULL AUTO_INCREMENT;
  
--
-- Indexes for commend table
--
ALTER TABLE `commend`
  ADD PRIMARY KEY (`ID`),
  MODIFY `ID` int(255) NOT NULL AUTO_INCREMENT;
  
--
-- Indexes for communities table
--
ALTER TABLE `communities`
  ADD PRIMARY KEY (`ID`),
  MODIFY `ID` int(255) NOT NULL AUTO_INCREMENT;
  
--
-- Indexes for config table
--
ALTER TABLE `config`
  ADD PRIMARY KEY (`ID`),
  MODIFY `ID` int(255) NOT NULL AUTO_INCREMENT;

--
-- Indexes for kicks table
--
ALTER TABLE `kicks`
  ADD PRIMARY KEY (`ID`),
  MODIFY `ID` int(255) NOT NULL AUTO_INCREMENT;

--
-- Indexes for notes table
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`ID`),
  MODIFY `ID` int(255) NOT NULL AUTO_INCREMENT;

--
-- Indexes for players table
--
ALTER TABLE `players`
  ADD PRIMARY KEY (`ID`),  
  ADD UNIQUE KEY `license` (`license`,`community`),
  MODIFY `ID` int(255) NOT NULL AUTO_INCREMENT;

--
-- Indexes for servers table
--
ALTER TABLE `servers`
  ADD PRIMARY KEY (`ID`),
  MODIFY `ID` int(255) NOT NULL AUTO_INCREMENT;

--
-- Indexes for support_comments table
--
ALTER TABLE `support_comments`
  ADD PRIMARY KEY (`ID`),
  MODIFY `ID` int(255) NOT NULL AUTO_INCREMENT;

--
-- Indexes for support_tickets table
--
ALTER TABLE `support_tickets`
  ADD PRIMARY KEY (`ID`),
  MODIFY `ID` int(255) NOT NULL AUTO_INCREMENT;

--
-- Indexes for users table
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`),
  MODIFY `ID` int(255) NOT NULL AUTO_INCREMENT,
  ADD UNIQUE KEY `steamid` (`steamid`);
COMMIT;


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
