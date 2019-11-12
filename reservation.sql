-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 12, 2019 at 10:05 PM
-- Server version: 5.7.26
-- PHP Version: 7.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `baileyc5`
--

-- --------------------------------------------------------

--
-- Table structure for table `reservation`
--

DROP TABLE IF EXISTS `reservation`;
CREATE TABLE IF NOT EXISTS `reservation` (
  `reserve_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_num` int(11) NOT NULL,
  `slot_id` int(11) NOT NULL,
  `dtop_id` int(11) NOT NULL,
  `b_num` int(11) NOT NULL,
  PRIMARY KEY (`reserve_id`),
  UNIQUE KEY `user_slot_unique` (`user_num`,`slot_id`),
  UNIQUE KEY `dtop_slot_unique` (`dtop_id`,`slot_id`),
  KEY `fk_slot` (`slot_id`),
  KEY `fk_b2` (`b_num`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `reservation`
--

INSERT INTO `reservation` (`reserve_id`, `user_num`, `slot_id`, `dtop_id`, `b_num`) VALUES
(1, 32, 1315, 8, 18),
(2, 30, 1321, 8, 18),
(3, 30, 1322, 8, 18),
(4, 32, 1327, 8, 18),
(5, 30, 1327, 3, 10),
(6, 32, 1328, 8, 18),
(7, 30, 1357, 3, 10),
(8, 30, 1316, 8, 18),
(9, 32, 1333, 8, 18),
(10, 30, 1334, 8, 18),
(11, 32, 1335, 8, 18),
(12, 32, 1317, 8, 18),
(13, 32, 1323, 8, 18),
(14, 32, 1329, 8, 18);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `reservation`
--
ALTER TABLE `reservation`
  ADD CONSTRAINT `fk_b2` FOREIGN KEY (`b_num`) REFERENCES `build` (`b_num`),
  ADD CONSTRAINT `fk_dtop2` FOREIGN KEY (`dtop_id`) REFERENCES `desktop` (`dtop_id`),
  ADD CONSTRAINT `fk_slot` FOREIGN KEY (`slot_id`) REFERENCES `timeslot` (`slot_id`),
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_num`) REFERENCES `user` (`user_num`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
