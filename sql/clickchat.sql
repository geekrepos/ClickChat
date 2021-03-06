-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 06, 2019 at 09:02 PM
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

-- --------------------------------------------------------

--
-- Table structure for table `friends_info`
--

CREATE TABLE `friends_info` (
  `F_ID` int(11) NOT NULL,
  `UserID` varchar(50) NOT NULL,
  `FriendID` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `friends_info`
--

INSERT INTO `friends_info` (`F_ID`, `UserID`, `FriendID`) VALUES
(1, 'avi', 'avidas'),
(2, 'avidas', 'avi'),
(3, 'ranjeet', 'avi'),
(4, 'avi', 'ranjeet');

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

--
-- Dumping data for table `message_store`
--

INSERT INTO `message_store` (`MessageID`, `SenderID`, `ReceiverID`, `Message`) VALUES
(1, 'ranjeet', 'avi', 'gdfsg'),
(2, 'ranjeet', 'avi', 'sdfgsdfg'),
(3, 'ranjeet', 'avi', 'sdfg'),
(4, 'ranjeet', 'avi', 'sdfg'),
(5, 'ranjeet', 'avi', 'sdf'),
(6, 'ranjeet', 'avi', 'gs'),
(7, 'ranjeet', 'avi', 'fgsd'),
(8, 'ranjeet', 'avi', ''),
(9, 'avi', 'ranjeet', 'hello'),
(10, 'ranjeet', 'avi', 'heelo bro'),
(11, 'avi', 'ranjeet', 'ds');

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
  `lastUpdatedTime` datetime NOT NULL,
  `randomuserid` int(11) NOT NULL,
  `photo_path` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_info`
--

INSERT INTO `user_info` (`ID`, `username`, `userid`, `password`, `online`, `lastUpdatedTime`, `randomuserid`, `photo_path`) VALUES
(23, 'ranjeet', 'ranjeet', '123', 0, '2019-10-07 00:31:44', 0, 'ranjeet.Capture.PNG'),
(24, 'Avinash', 'avi', '123', 0, '2019-10-07 00:22:22', 0, 'avi.004.jpg');

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
  MODIFY `F_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `message_store`
--
ALTER TABLE `message_store`
  MODIFY `MessageID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `user_info`
--
ALTER TABLE `user_info`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
