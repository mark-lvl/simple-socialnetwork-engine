-- phpMyAdmin SQL Dump
-- version 3.2.2.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 08, 2010 at 06:20 PM
-- Server version: 5.1.37
-- PHP Version: 5.2.10-2ubuntu6.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `parspake`
--
CREATE DATABASE `parspake` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `parspake`;

-- --------------------------------------------------------

--
-- Table structure for table `relations`
--

CREATE TABLE IF NOT EXISTS `relations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `inviter` int(10) unsigned NOT NULL,
  `guest` int(10) unsigned NOT NULL,
  `invitation_date` datetime NOT NULL,
  `status` tinyint(1) unsigned zerofill NOT NULL COMMENT '1:ok, 2:denied, 3:ignored',
  `answer_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=89 ;

--
-- Dumping data for table `relations`
--

INSERT INTO `relations` (`id`, `inviter`, `guest`, `invitation_date`, `status`, `answer_date`) VALUES
(88, 1, 2, '2010-11-07 19:52:20', 0, '2010-11-07 19:52:27');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE IF NOT EXISTS `sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(16) NOT NULL DEFAULT '0',
  `user_agent` varchar(50) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text NOT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`session_id`, `ip_address`, `user_agent`, `last_activity`, `user_data`) VALUES
('6041d818a4e80a580be4e4230a10a094', '127.0.0.3', 'Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.2.8', 1289226302, 'a:12:{s:2:"id";s:1:"1";s:10:"first_name";s:7:"masdlkj";s:9:"last_name";s:9:"jhgjhgjhg";s:5:"email";s:13:"mark@mark.com";s:8:"password";s:32:"f5bb0c8de146c67b44babbf4e6584cc0";s:3:"sex";s:1:"1";s:15:"registration_ip";s:9:"127.0.0.3";s:17:"registration_date";s:19:"2010-11-06 20:01:30";s:6:"logins";s:2:"20";s:13:"last_login_ip";s:0:"";s:15:"last_login_date";s:19:"2010-11-07 18:15:18";s:9:"logged_in";s:1:"1";}');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(250) CHARACTER SET utf8 NOT NULL,
  `last_name` varchar(250) CHARACTER SET utf8 NOT NULL,
  `email` varchar(250) CHARACTER SET utf8 NOT NULL,
  `password` varchar(250) CHARACTER SET utf8 NOT NULL,
  `sex` tinyint(1) unsigned zerofill NOT NULL COMMENT '0:male',
  `registration_ip` varchar(45) CHARACTER SET utf8 NOT NULL,
  `registration_date` datetime NOT NULL,
  `logins` int(10) unsigned NOT NULL,
  `last_login_ip` varchar(45) CHARACTER SET utf8 NOT NULL,
  `last_login_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password`, `sex`, `registration_ip`, `registration_date`, `logins`, `last_login_ip`, `last_login_date`) VALUES
(1, 'masdlkj', 'jhgjhgjhg', 'mark@mark.com', 'f5bb0c8de146c67b44babbf4e6584cc0', 1, '127.0.0.3', '2010-11-06 20:01:30', 21, '', '2010-11-08 16:16:27'),
(2, 'asdasda', 'hhhhhhhhhhhhhh', 'mark2@mark2.com', 'bb2d91d0fbbebe8719509ed0f865c63f', 1, '127.0.0.3', '2010-11-07 19:50:50', 0, '', '0000-00-00 00:00:00');
