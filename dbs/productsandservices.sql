-- phpMyAdmin SQL Dump
-- version 3.1.5
-- http://www.phpmyadmin.net
--
-- Host: remote-mysql3.servage.net
-- Generation Time: Feb 08, 2014 at 02:01 AM
-- Server version: 5.5.25
-- PHP Version: 5.2.42-servage26

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `nov2013`
--

-- --------------------------------------------------------

--
-- Table structure for table `productsandservices`
--

CREATE TABLE IF NOT EXISTS `productsandservices` (
  `product_id` varchar(50) NOT NULL,
  `user_id` varchar(50) DEFAULT NULL,
  `product_name` varchar(50) DEFAULT NULL,
  `product_description` text,
  `product_creation_date` bigint(20) NOT NULL,
  `product_unique_code` varchar(200) NOT NULL,
  `product_url` varchar(200) NOT NULL,
  `product_type` enum('product','service') DEFAULT NULL,
  `product_images` text,
  `product_status` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `productsandservices`
--

