-- phpMyAdmin SQL Dump
-- version 4.1.8
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2018-01-21 14:48:29
-- 服务器版本： 5.6.21-log
-- PHP Version: 5.5.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `linyiit_student`
--
CREATE DATABASE IF NOT EXISTS `linyiit_student` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `linyiit_student`;

-- --------------------------------------------------------

--
-- 表的结构 `gejun_kanjia`
--

CREATE TABLE IF NOT EXISTS `gejun_kanjia` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `openid` varchar(32) NOT NULL,
  `zopenid` varchar(32) NOT NULL,
  `kmoney` int(11) NOT NULL,
  `pubtime` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=24 ;

--
-- 转存表中的数据 `gejun_kanjia`
--

INSERT INTO `gejun_kanjia` (`id`, `openid`, `zopenid`, `kmoney`, `pubtime`) VALUES
(20, 'opnOvt8DXi6F6iOhEo6QxB35P-CA', 'opnOvt8DXi6F6iOhEo6QxB35P-CA', 100, '2018-01-21 10:51:07'),
(21, 'opnOvtyMPh6uOiBxjNACtXFqACSY', 'opnOvt8DXi6F6iOhEo6QxB35P-CA', 100, '2018-01-21 10:55:05'),
(22, 'opnOvt8DXi6F6iOhEo6QxB35P-CA', 'opnOvtyMPh6uOiBxjNACtXFqACSY', 100, '2018-01-21 10:57:14'),
(23, 'opnOvtyMPh6uOiBxjNACtXFqACSY', 'opnOvtyMPh6uOiBxjNACtXFqACSY', 100, '2018-01-21 13:41:35');

-- --------------------------------------------------------

--
-- 表的结构 `gejun_users`
--

CREATE TABLE IF NOT EXISTS `gejun_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `telphone` varchar(11) NOT NULL,
  `openid` varchar(32) NOT NULL,
  `money` int(11) NOT NULL DEFAULT '1000',
  `pubtime` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- 转存表中的数据 `gejun_users`
--

INSERT INTO `gejun_users` (`id`, `username`, `telphone`, `openid`, `money`, `pubtime`) VALUES
(12, '1111', '11111', 'opnOvt8DXi6F6iOhEo6QxB35P-CA', 800, '2018-01-21 10:50:15'),
(13, '111', '222', 'opnOvtyMPh6uOiBxjNACtXFqACSY', 800, '2018-01-21 10:56:26');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
