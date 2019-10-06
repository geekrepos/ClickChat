-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 05, 2019 at 05:48 AM
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
-- Database: `clickchat`
--
CREATE DATABASE IF NOT EXISTS `clickchat` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `clickchat`;

-- --------------------------------------------------------

--
-- Table structure for table `friends_info`
--

CREATE TABLE `friends_info` (
  `F_ID` int(11) NOT NULL,
  `UserID` varchar(50) NOT NULL,
  `FriendID` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `message_store`
--

CREATE TABLE `message_store` (
  `MessageID` int(11) NOT NULL,
  `SenderID` varchar(50) NOT NULL,
  `ReceiverID` varchar(50) NOT NULL,
  `Message` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_info`
--

CREATE TABLE `user_info` (
  `ID` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `userid` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `online` int(11) NOT NULL,
  `randomuserid` int(11) NOT NULL,
  `photo_path` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_info`
--

INSERT INTO `user_info` (`ID`, `username`, `userid`, `password`, `online`, `randomuserid`, `photo_path`) VALUES
(21, 'Avinash', 'avi', '123', 0, 0, 'avi.004.jpg'),
(22, 'Avinashdsda', 'avidas', '123', 0, 0, 'avidas.004.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `friends_info`
--
ALTER TABLE `friends_info`
  ADD PRIMARY KEY (`F_ID`);

--
-- Indexes for table `message_store`
--
ALTER TABLE `message_store`
  ADD PRIMARY KEY (`MessageID`);

--
-- Indexes for table `user_info`
--
ALTER TABLE `user_info`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `friends_info`
--
ALTER TABLE `friends_info`
  MODIFY `F_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `message_store`
--
ALTER TABLE `message_store`
  MODIFY `MessageID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_info`
--
ALTER TABLE `user_info`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
