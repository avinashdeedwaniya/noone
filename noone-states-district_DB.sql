-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 25, 2015 at 09:07 AM
-- Server version: 5.5.27
-- PHP Version: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `wptest`
--

-- --------------------------------------------------------

--
-- Table structure for table `wp_district_list`
--

CREATE TABLE IF NOT EXISTS `wp_district_list` (
  `district_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wp_district_list`
--

INSERT INTO `wp_district_list` (`district_name`) VALUES
('AJMER'),
('ALWAR'),
('BANSWARA'),
('BARAN'),
('BARMER'),
('BHARATPUR'),
('BHILWARA'),
('BIKANER'),
('BUNDI'),
('CHITTORGARH'),
('CHURU'),
('DAUSA'),
('DHOLPUR'),
('DUNGARPUR'),
('GANGANAGAR'),
('HANUMANGARH'),
('JAIPUR'),
('JAISALAMER'),
('JAISALMER'),
('JALORE'),
('JHALAWAR'),
('JHUNJHUNUN'),
('JODHPUR'),
('KARAULI'),
('KOTA'),
('NAGAUR'),
('PALI'),
('RAJSAMAND'),
('SAWAI MADHOPUR'),
('SIKAR'),
('SIROHI'),
('TONK'),
('UDAIPUR');

-- --------------------------------------------------------

--
-- Table structure for table `wp_states_list`
--

CREATE TABLE IF NOT EXISTS `wp_states_list` (
  `state_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wp_states_list`
--

INSERT INTO `wp_states_list` (`state_name`) VALUES
('ANDHRA PRADESH'),
('ASSAM'),
('ARUNACHAL PRADESH'),
('GUJRAT'),
('BIHAR'),
('HARYANA'),
('HIMACHAL PRADESH'),
('JAMMU & KASHMIR'),
('KARNATAKA'),
('KERALA'),
('MADHYA PRADESH'),
('MAHARASHTRA'),
('MANIPUR'),
('MEGHALAYA'),
('MIZORAM'),
('NAGALAND'),
('ORISSA'),
('PUNJAB'),
('RAJASTHAN'),
('SIKKIM'),
('TAMIL NADU'),
('TRIPURA'),
('UTTAR PRADESH'),
('WEST BENGAL'),
('DELHI'),
('GOA'),
('PONDICHERY'),
('LAKSHDWEEP'),
('DAMAN & DIU'),
('DADRA & NAGAR'),
('CHANDIGARH'),
('ANDAMAN & NICOBAR'),
('UTTARANCHAL'),
('JHARKHAND'),
('CHATTISGARH');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
