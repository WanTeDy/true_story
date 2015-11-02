-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Nov 01, 2015 at 09:41 PM
-- Server version: 5.5.25
-- PHP Version: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `chat_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `dialogs`
--

CREATE TABLE IF NOT EXISTS `dialogs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dial_name` varchar(30) NOT NULL,
  `dial_type` varchar(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=113 ;

--
-- Dumping data for table `dialogs`
--

INSERT INTO `dialogs` (`id`, `dial_name`, `dial_type`) VALUES
(112, 'Общий канал', 'public_d');

-- --------------------------------------------------------

--
-- Table structure for table `dialogs_message`
--

CREATE TABLE IF NOT EXISTS `dialogs_message` (
  `dialog_id` int(11) NOT NULL,
  `text` text NOT NULL,
  `name` varchar(20) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `dialogs_users`
--

CREATE TABLE IF NOT EXISTS `dialogs_users` (
  `dialog_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  UNIQUE KEY `dialog_id` (`dialog_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dialogs_users`
--

INSERT INTO `dialogs_users` (`dialog_id`, `user_id`) VALUES
(112, 3);

-- --------------------------------------------------------

--
-- Table structure for table `online`
--

CREATE TABLE IF NOT EXISTS `online` (
  `sid` varchar(50) NOT NULL,
  `puttime` datetime NOT NULL,
  `user` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `online`
--

INSERT INTO `online` (`sid`, `puttime`, `user`) VALUES
('mkt8p8becqe0ieph2vqek4mik0', '2015-11-01 21:27:02', 'Dima');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `password` char(32) NOT NULL,
  `privelege` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `password`, `privelege`) VALUES
(1, 'Серж', 'af744c36b367bf4e6e2890cf45db41b3', 0),
(2, 'admin', '3b41bf6b2d28163ee961d24d88c12bb5', 1),
(3, 'Dima', '5c3759ee6460e1c2f0510a80a9fd8463', 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
