-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 27, 2019 at 12:38 PM
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
-- Database: `testlimited`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_triggers` ()  BEGIN
    DECLARE finished INTEGER DEFAULT 0;
    DECLARE sql_id INTEGER(11);
    DECLARE sql_query TEXT DEFAULT "";
 
    -- declare cursor for sqlQueryData
    DEClARE sqlQueryData 
        CURSOR FOR 
            SELECT `id`, `trigger_sql` FROM testsql.`insert_trigger`;
 
    -- declare NOT FOUND handler
    DECLARE CONTINUE HANDLER 
        FOR NOT FOUND SET finished = 1;
 
    OPEN sqlQueryData;
 
    getSqlQuery: LOOP
        FETCH sqlQueryData INTO sql_id, sql_query;
        IF finished = 1 THEN 
            LEAVE getSqlQuery;
        END IF;
        -- build sqlQueryList
        SET @sql = sql_query;
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;

        DELETE FROM testsql.`insert_trigger` WHERE `id` = sql_id;
        
    END LOOP getSqlQuery;
    CLOSE sqlQueryData;
 
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `age` int(3) NOT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `age`, `email`) VALUES
(1, 'Test 1', 33, 'test1@gmail.com'),
(2, 'Test 2', 24, 'test2@gmail.com');

--
-- Triggers `users`
--
DELIMITER $$
CREATE TRIGGER `add_delete_trigger_sql` AFTER DELETE ON `users` FOR EACH ROW INSERT INTO testsql.`insert_trigger` 
SET trigger_status = 'DELETE',
  `trigger_sql`= CONCAT("DELETE FROM testfull.`users` WHERE `id`='", OLD.id, "';")
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `add_insert_trigger_sql` AFTER INSERT ON `users` FOR EACH ROW INSERT INTO testsql.`insert_trigger` 
SET trigger_status = 'INSERT',
  `trigger_sql`=CONCAT("INSERT INTO testfull.`users` SET `id`='", NEW.id, "', `name`='", NEW.name, "', `age`='", NEW.age, "', `email`='", NEW.email,"';")
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `add_update_trigger_sql` AFTER UPDATE ON `users` FOR EACH ROW INSERT INTO testsql.`insert_trigger` 
SET trigger_status = 'UPDATE',
  `trigger_sql`= CONCAT("UPDATE testfull.`users` SET `name`='", NEW.name, "', `age`='", NEW.age, "', `email`='", NEW.email,"' WHERE `id`='", NEW.id, "';")
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
