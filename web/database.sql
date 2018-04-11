-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 12, 2018 at 12:45 AM
-- Server version: 5.6.38
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cadojrpc_admin`
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

--
-- Dumping data for table `kicks`
--

INSERT INTO `kicks` (`ID`, `license`, `reason`, `staff_name`, `staff_steamid`, `time`) VALUES
(1, 'testing', 'temsptakjdhgbmjbndamsdbvkas', '', '76561198113581050', '1523471918'),
(2, 'license:b60077947efe4473eac942fe658011a125a24458', 'testing system', 'Arthur Mitchell', '76561198113581050', '1523472120'),
(3, 'license:b60077947efe4473eac942fe658011a125a24458', 'TEST NUMBER 1', 'Arthur Mitchell', '76561198113581050', '1523472437'),
(4, 'license:b60077947efe4473eac942fe658011a125a24458', 'tes', 'Arthur Mitchell', '76561198113581050', '1523472783'),
(5, 'license:2cf94bed992acb782d2931b9c6d98d530f6d0769', 'test', 'Arthur Mitchell', '76561198113581050', '1523475043'),
(6, 'license:78ea6501bcbe4ed3a582359a878ef8ddd168fd5a', 'Testing blah blah', 'Arthur Mitchell', '76561198113581050', '1523481262'),
(7, 'license:52437686dd7274d6120402a98046b135a9724f4d', ' ', 'Arthur Mitchell', '76561198113581050', '1523484226');

-- --------------------------------------------------------

--
-- Table structure for table `players`
--

CREATE TABLE `players` (
  `ID` int(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `license` varchar(255) NOT NULL,
  `steam` varchar(255) NOT NULL,
  `playtime` int(255) NOT NULL DEFAULT '1',
  `firstjoined` varchar(255) NOT NULL,
  `lastplayed` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`ID`, `name`, `steamid`, `rank`) VALUES
(1, 'Arthur Mitchell (Example)', '76561198113581050', 'director');

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
-- AUTO_INCREMENT for table `kicks`
--
ALTER TABLE `kicks`
  MODIFY `ID` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `players`
--
ALTER TABLE `players`
  MODIFY `ID` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `servers`
--
ALTER TABLE `servers`
  MODIFY `ID` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `ID` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `warnings`
--
ALTER TABLE `warnings`
  MODIFY `ID` int(255) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
