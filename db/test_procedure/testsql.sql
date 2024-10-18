-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 27, 2019 at 12:36 PM
-- Server version: 5.5.54-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `testsql`
--

-- --------------------------------------------------------

--
-- Table structure for table `insert_trigger`
--

CREATE TABLE `insert_trigger` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) DEFAULT NULL,
  `trigger_sql` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `insert_trigger`
--

INSERT INTO `insert_trigger` (`id`, `trigger_status`, `trigger_sql`) VALUES
(1, 'INSERT', 'INSERT INTO testfull.`users` SET `id`=\'1\', `name`=\'Test 1\', `age`=\'32\', `email`=\'test@gmail.com\';'),
(2, 'INSERT', 'INSERT INTO testfull.`users` SET `id`=\'2\', `name`=\'Test 2\', `age`=\'23\', `email`=\'test2@gmail.com\';'),
(3, 'UPDATE', 'UPDATE testfull.`users` SET `name`=\'Test 1\', `age`=\'33\', `email`=\'test1@gmail.com\' WHERE `id`=\'1\';'),
(4, 'UPDATE', 'UPDATE testfull.`users` SET `name`=\'Test 2\', `age`=\'24\', `email`=\'test2@gmail.com\' WHERE `id`=\'2\';'),
(5, 'INSERT', 'INSERT INTO testfull.`users` SET `id`=\'3\', `name`=\'Test 3\', `age`=\'55\', `email`=\'sad\';'),
(6, 'UPDATE', 'UPDATE testfull.`users` SET `name`=\'Test 3\', `age`=\'55\', `email`=\'test3@gmail.com\' WHERE `id`=\'3\';'),
(7, 'DELETE', 'DELETE FROM testfull.`users` WHERE `id`=\'3\';');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `insert_trigger`
--
ALTER TABLE `insert_trigger`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `insert_trigger`
--
ALTER TABLE `insert_trigger`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
