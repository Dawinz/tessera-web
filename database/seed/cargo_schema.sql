-- MariaDB dump 10.19  Distrib 10.4.28-MariaDB, for osx10.10 (x86_64)
--
-- Host: localhost    Database: cargo
-- ------------------------------------------------------
-- Server version	10.4.28-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `accout_transaction`
--

DROP TABLE IF EXISTS `accout_transaction`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `accout_transaction` (
  `id` int(11) NOT NULL,
  `buss` varchar(60) NOT NULL,
  `trip_date` varchar(20) NOT NULL,
  `income` int(11) NOT NULL,
  `expenses` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `analyzer`
--

DROP TABLE IF EXISTS `analyzer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `analyzer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_amt` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `acc_id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `quanitity` int(11) NOT NULL,
  `ammount_left` int(11) NOT NULL,
  `date` varchar(30) NOT NULL,
  `checked` varchar(30) NOT NULL,
  `vehicle_name` varchar(100) NOT NULL,
  `detail` text NOT NULL,
  `day` varchar(78) NOT NULL,
  `month` varchar(78) NOT NULL,
  `year` varchar(78) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `assign_route`
--

DROP TABLE IF EXISTS `assign_route`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `assign_route` (
  `id` int(11) NOT NULL,
  `route` varchar(200) NOT NULL,
  `route_name` varchar(200) NOT NULL,
  `username` varchar(200) NOT NULL,
  `point` varchar(100) NOT NULL,
  `main_route` varchar(300) NOT NULL,
  `end_point` varchar(100) NOT NULL,
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `assigned_routes`
--

DROP TABLE IF EXISTS `assigned_routes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `assigned_routes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `route_id` int(11) DEFAULT NULL,
  `start_route` varchar(200) DEFAULT NULL,
  `end_route` varchar(20) DEFAULT NULL,
  `username` varchar(200) DEFAULT NULL,
  `active` varchar(100) DEFAULT NULL,
  `selected` varchar(100) DEFAULT NULL,
  `active_tab` varchar(100) DEFAULT NULL,
  `fair_price` int(11) DEFAULT NULL,
  `fair_price_international` int(11) DEFAULT NULL,
  `currency` varchar(200) DEFAULT NULL,
  `currency_symbol` varchar(10) DEFAULT NULL,
  `detail` text DEFAULT NULL,
  `route_name` varchar(100) DEFAULT NULL,
  `route_full_name` varchar(200) DEFAULT NULL,
  `preference` varchar(11) DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=222 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `attachment`
--

DROP TABLE IF EXISTS `attachment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `attachment` (
  `id` int(11) NOT NULL,
  `buss` varchar(60) NOT NULL,
  `trip_date` varchar(30) NOT NULL,
  `file_name` varchar(100) NOT NULL,
  `route_name` varchar(200) NOT NULL,
  `main_route` varchar(200) NOT NULL,
  `ogname` varchar(200) NOT NULL,
  `point` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `booking`
--

DROP TABLE IF EXISTS `booking`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `booking` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `seat_no` varchar(10) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `phone_no` varchar(100) NOT NULL,
  `validation` varchar(10) NOT NULL,
  `rout_id` varchar(10) NOT NULL,
  `issur` varchar(90) NOT NULL,
  `status` varchar(30) NOT NULL,
  `exp_time` varchar(30) NOT NULL,
  `trip_date` varchar(40) NOT NULL,
  `book_date` varchar(20) NOT NULL,
  `book_time` varchar(10) NOT NULL,
  `buss` varchar(20) NOT NULL,
  `cost` int(11) NOT NULL,
  `international_cost` int(11) NOT NULL,
  `route_day_id` int(11) NOT NULL,
  `day` varchar(40) NOT NULL,
  `month` varchar(40) NOT NULL,
  `year` varchar(40) NOT NULL,
  `start_location` varchar(400) NOT NULL,
  `ending_location` varchar(400) NOT NULL,
  `reason` text NOT NULL,
  `origin_status` varchar(80) NOT NULL,
  `booker` varchar(100) NOT NULL,
  `start_location_sub` varchar(100) NOT NULL,
  `end_location_sub` varchar(100) NOT NULL,
  `gender` varchar(30) NOT NULL,
  `age_group` varchar(50) NOT NULL,
  `updater` varchar(100) NOT NULL,
  `route_day` int(11) NOT NULL,
  `route_start` varchar(200) NOT NULL,
  `route_end` varchar(200) NOT NULL,
  `initial` varchar(200) NOT NULL,
  `currency` varchar(300) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=502 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cash`
--

DROP TABLE IF EXISTS `cash`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cash` (
  `id` int(11) NOT NULL,
  `opening_stock` int(11) NOT NULL,
  `issuer` varchar(30) NOT NULL,
  `date` varchar(30) NOT NULL,
  `time` varchar(30) NOT NULL,
  `authorizer` text NOT NULL,
  `open_date` varchar(20) NOT NULL,
  `stock_no` int(11) NOT NULL,
  `ammount_remain` int(11) NOT NULL,
  `initial_am_remain` int(11) NOT NULL,
  `status` varchar(20) NOT NULL,
  `percentage` int(11) NOT NULL,
  `color` varchar(300) NOT NULL,
  `amount_dollar` decimal(11,2) NOT NULL,
  `account` varchar(10) NOT NULL,
  `day` varchar(20) NOT NULL,
  `month` varchar(20) NOT NULL,
  `year` varchar(20) NOT NULL,
  `bank` varchar(60) NOT NULL,
  `check_no` varchar(100) NOT NULL,
  `check_to` varchar(60) NOT NULL,
  `vehicle` varchar(78) NOT NULL,
  `name` text NOT NULL,
  `route_name` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cash_evaluation`
--

DROP TABLE IF EXISTS `cash_evaluation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cash_evaluation` (
  `id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `amount_remain` int(11) NOT NULL,
  `route_name` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cash_hand`
--

DROP TABLE IF EXISTS `cash_hand`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cash_hand` (
  `id` int(11) NOT NULL,
  `open_date` varchar(20) NOT NULL,
  `account` varchar(30) NOT NULL,
  `authorizer` varchar(80) NOT NULL,
  `issuer` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cash_hand_expenses`
--

DROP TABLE IF EXISTS `cash_hand_expenses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cash_hand_expenses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `image` varchar(1000) NOT NULL,
  `link` varchar(200) NOT NULL,
  `arrangment` int(11) NOT NULL,
  `category` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `check_category`
--

DROP TABLE IF EXISTS `check_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `check_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `location` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=175 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `checked_manifest`
--

DROP TABLE IF EXISTS `checked_manifest`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `checked_manifest` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `trip_date` varchar(30) NOT NULL,
  `status` varchar(10) NOT NULL,
  `times` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1940 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `code`
--

DROP TABLE IF EXISTS `code`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `code` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(30) DEFAULT NULL,
  `full_code` varchar(30) DEFAULT NULL,
  `location` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `constants`
--

DROP TABLE IF EXISTS `constants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `constants` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `route_name` varchar(200) NOT NULL,
  `price_value` int(11) NOT NULL,
  `currency` varchar(20) NOT NULL,
  `status` varchar(20) NOT NULL,
  `route_category` varchar(200) NOT NULL,
  `name_category` varchar(200) NOT NULL,
  `affect` varchar(200) NOT NULL,
  `vehicle` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `constant`
--

DROP TABLE IF EXISTS `constant`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `constant` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `amount` int(11) NOT NULL,
  `status` varchar(70) NOT NULL,
  `location` varchar(40) NOT NULL,
  `status_flow` varchar(30) NOT NULL,
  `currency` varchar(12) NOT NULL,
  `vehicle` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=200 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `contact`
--

DROP TABLE IF EXISTS `contact`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contact` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `s_name` varchar(100) DEFAULT NULL,
  `s_no` varchar(100) DEFAULT NULL,
  `r_name` varchar(100) DEFAULT NULL,
  `r_no` varchar(100) DEFAULT NULL,
  `s_no_code` varchar(10) DEFAULT NULL,
  `r_no_code` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=84894 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `country`
--

DROP TABLE IF EXISTS `country`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `country` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `iso` char(2) NOT NULL,
  `name` varchar(80) NOT NULL,
  `nicename` varchar(80) NOT NULL,
  `iso3` char(3) DEFAULT NULL,
  `numcode` smallint(6) DEFAULT NULL,
  `phonecode` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=240 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `country_code`
--

DROP TABLE IF EXISTS `country_code`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `country_code` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `route_name` varchar(100) NOT NULL,
  `code` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `currency`
--

DROP TABLE IF EXISTS `currency`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `currency` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL,
  `status` varchar(20) NOT NULL,
  `rate` int(11) NOT NULL,
  `initial` varchar(30) NOT NULL,
  `country` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `currency_value`
--

DROP TABLE IF EXISTS `currency_value`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `currency_value` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_point` varchar(100) NOT NULL,
  `second_point` varchar(100) NOT NULL,
  `value` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `daily_charges`
--

DROP TABLE IF EXISTS `daily_charges`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `daily_charges` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `to_be` int(11) NOT NULL,
  `paid` int(11) NOT NULL,
  `date_insert` varchar(15) NOT NULL,
  `location` varchar(300) NOT NULL,
  `no_to_be` int(11) NOT NULL,
  `no_paid` int(11) NOT NULL,
  `no_due` int(11) NOT NULL,
  `sort` int(11) NOT NULL,
  `month` varchar(20) NOT NULL,
  `status` varchar(20) NOT NULL,
  `username` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `date_insert` (`date_insert`),
  KEY `location` (`location`)
) ENGINE=InnoDB AUTO_INCREMENT=6657 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `daily_year`
--

DROP TABLE IF EXISTS `daily_year`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `daily_year` (
  `id` int(11) NOT NULL,
  `name` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `date_total`
--

DROP TABLE IF EXISTS `date_total`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `date_total` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` varchar(40) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `expenses`
--

DROP TABLE IF EXISTS `expenses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `expenses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `approved` varchar(30) NOT NULL,
  `date` varchar(30) NOT NULL,
  `detail` text NOT NULL,
  `ammout` varchar(30) NOT NULL,
  `ammount_curr` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `time` varchar(20) NOT NULL,
  `insert_date_time` varchar(30) NOT NULL,
  `code` int(11) NOT NULL,
  `stock_no` int(11) NOT NULL,
  `stock_ammount` int(11) NOT NULL,
  `ammount_remain` int(11) NOT NULL,
  `name` varchar(80) NOT NULL,
  `account` varchar(30) NOT NULL,
  `took` varchar(50) NOT NULL,
  `out_cast` varchar(30) NOT NULL,
  `day` varchar(30) NOT NULL,
  `month` varchar(30) NOT NULL,
  `year` varchar(30) NOT NULL,
  `balance` int(11) NOT NULL,
  `account_from` varchar(30) NOT NULL,
  `icon` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=266 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `expenses_list`
--

DROP TABLE IF EXISTS `expenses_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `expenses_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `type` varchar(100) NOT NULL,
  `date` varchar(20) NOT NULL,
  `time` varchar(20) NOT NULL,
  `uploader` varchar(20) NOT NULL,
  `status` varchar(100) NOT NULL,
  `location` varchar(200) NOT NULL,
  `amount` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `files`
--

DROP TABLE IF EXISTS `files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(300) NOT NULL,
  `parentFolder` varchar(10) NOT NULL,
  `new` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `history`
--

DROP TABLE IF EXISTS `history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `history` (
  `id` int(11) NOT NULL,
  `category` varchar(30) NOT NULL,
  `status` varchar(30) NOT NULL,
  `username` varchar(30) NOT NULL,
  `fullname` varchar(30) NOT NULL,
  `date` varchar(30) NOT NULL,
  `time` varchar(30) NOT NULL,
  `changes` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `id`
--

DROP TABLE IF EXISTS `id`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `id` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(30) NOT NULL,
  `fullname` varchar(200) NOT NULL,
  `payed` int(11) NOT NULL,
  `tobe` int(11) NOT NULL,
  `total` int(11) NOT NULL,
  `date_change` varchar(60) NOT NULL,
  `date_insert` varchar(60) NOT NULL,
  `time` varchar(30) NOT NULL,
  `id2` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2665 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `income_category`
--

DROP TABLE IF EXISTS `income_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `income_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(300) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `job_card`
--

DROP TABLE IF EXISTS `job_card`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_card` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `date` varchar(30) NOT NULL,
  `amount` int(11) NOT NULL,
  `item` varchar(30) NOT NULL,
  `username` varchar(100) NOT NULL,
  `status` varchar(10) NOT NULL,
  `boostrap` varchar(20) NOT NULL,
  `route_name` varchar(200) NOT NULL,
  `user_checked` varchar(20) NOT NULL,
  `checked_count` int(11) NOT NULL,
  `analysed` varchar(20) NOT NULL,
  `month` varchar(30) NOT NULL,
  `year` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `job_card_detail`
--

DROP TABLE IF EXISTS `job_card_detail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_card_detail` (
  `id` int(11) NOT NULL,
  `first_date` varchar(20) NOT NULL,
  `month` varchar(20) NOT NULL,
  `year` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `job_card_entires`
--

DROP TABLE IF EXISTS `job_card_entires`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_card_entires` (
  `id` int(11) NOT NULL,
  `date` varchar(20) NOT NULL,
  `buss` varchar(20) NOT NULL,
  `item` varchar(300) NOT NULL,
  `qty` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `date_insert` varchar(30) NOT NULL,
  `time_insert` varchar(11) NOT NULL,
  `status` varchar(30) NOT NULL,
  `route_name` varchar(200) NOT NULL,
  `username` varchar(300) NOT NULL,
  `detail` text NOT NULL,
  `month` varchar(30) NOT NULL,
  `year` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `job_card_vehicle`
--

DROP TABLE IF EXISTS `job_card_vehicle`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_card_vehicle` (
  `id` int(11) NOT NULL,
  `date` varchar(30) NOT NULL,
  `vehicle` varchar(100) NOT NULL,
  `month` varchar(50) NOT NULL,
  `year` int(11) NOT NULL,
  `route_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `load_sms`
--

DROP TABLE IF EXISTS `load_sms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `load_sms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(100) NOT NULL,
  `no` varchar(30) NOT NULL,
  `sms` text NOT NULL,
  `code_generated` varchar(300) NOT NULL,
  `status` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `locations_names`
--

DROP TABLE IF EXISTS `locations_names`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `locations_names` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `logs`
--

DROP TABLE IF EXISTS `logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(60) NOT NULL,
  `page` text NOT NULL,
  `date` varchar(60) NOT NULL,
  `time` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=522636 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mainfest_code`
--

DROP TABLE IF EXISTS `mainfest_code`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mainfest_code` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` int(11) NOT NULL,
  `route_name` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `maintance`
--

DROP TABLE IF EXISTS `maintance`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `maintance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `manifest`
--

DROP TABLE IF EXISTS `manifest`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `manifest` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(20) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `amount` int(11) DEFAULT NULL,
  `trip_date` varchar(30) DEFAULT NULL,
  `buss` varchar(100) DEFAULT NULL,
  `payed` int(11) DEFAULT NULL,
  `to_be` int(11) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `uploader` varchar(30) DEFAULT NULL,
  `q_left` varchar(30) DEFAULT NULL,
  `quantity` varchar(10) DEFAULT NULL,
  `detail` text NOT NULL,
  `last_left` int(11) DEFAULT NULL,
  `parcel_uploader` varchar(200) DEFAULT NULL,
  `checked` varchar(30) DEFAULT NULL,
  `timetaken` varchar(50) DEFAULT NULL,
  `username_taken` varchar(300) DEFAULT NULL,
  `updated` varchar(20) DEFAULT NULL,
  `id2` int(11) DEFAULT NULL,
  `parcel_route` varchar(200) DEFAULT NULL,
  `loan` int(11) DEFAULT NULL,
  `parcel_date` varchar(30) DEFAULT NULL,
  `quantity_insert` int(11) DEFAULT NULL,
  `weight_name` varchar(20) DEFAULT NULL,
  `weight_value` int(11) DEFAULT NULL,
  `lugg_value` int(11) DEFAULT NULL,
  `tranist` varchar(200) NOT NULL,
  `sender_no` varchar(20) NOT NULL,
  `reciever_no` varchar(20) NOT NULL,
  `date_insert` varchar(40) NOT NULL,
  `changes` varchar(200) NOT NULL,
  `currency` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `manifest_index` (`code`,`trip_date`,`buss`),
  KEY `search_query` (`checked`,`parcel_route`)
) ENGINE=InnoDB AUTO_INCREMENT=95 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `manifest_dates`
--

DROP TABLE IF EXISTS `manifest_dates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `manifest_dates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `trip_date` varchar(50) NOT NULL,
  `buss` varchar(50) NOT NULL,
  `parcel_date` varchar(50) NOT NULL,
  `route` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17187 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `manifest_deleted`
--

DROP TABLE IF EXISTS `manifest_deleted`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `manifest_deleted` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id2` int(11) NOT NULL,
  `code` varchar(10) NOT NULL,
  `updated` varchar(10) NOT NULL,
  `username` varchar(200) NOT NULL,
  `status` varchar(200) NOT NULL,
  `to_be` int(11) NOT NULL,
  `payed` int(11) NOT NULL,
  `date_insert` varchar(100) NOT NULL,
  `amount` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1988 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `manifest_entires`
--

DROP TABLE IF EXISTS `manifest_entires`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `manifest_entires` (
  `id` int(11) NOT NULL,
  `payed` int(11) NOT NULL,
  `to_be` int(11) NOT NULL,
  `trip_date` varchar(30) NOT NULL,
  `buss` varchar(100) NOT NULL,
  `main_route` varchar(100) NOT NULL,
  `route_name` varchar(100) NOT NULL,
  `start_route` varchar(20) NOT NULL,
  `end_route` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `manifest_exp`
--

DROP TABLE IF EXISTS `manifest_exp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `manifest_exp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `buss` varchar(50) NOT NULL,
  `trip_date` varchar(50) NOT NULL,
  `amount` int(11) NOT NULL,
  `income_amt` int(11) NOT NULL,
  `to_be` int(11) NOT NULL,
  `payed` int(11) NOT NULL,
  `insert_ammount` int(11) NOT NULL,
  `status` varchar(30) NOT NULL,
  `detail` text NOT NULL,
  `insert_date` varchar(30) NOT NULL,
  `v_no` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `status_name` varchar(30) NOT NULL,
  `status_status` varchar(30) NOT NULL,
  `time` varchar(10) NOT NULL,
  `amount_dollar` decimal(11,2) NOT NULL,
  `to_be_dollar` decimal(11,2) NOT NULL,
  `pay_dollar` decimal(11,2) NOT NULL,
  `amount_insert_dollar` decimal(11,2) NOT NULL,
  `income_ammount_dollar` decimal(11,2) NOT NULL,
  `rate` varchar(15) NOT NULL,
  `day` varchar(20) NOT NULL,
  `month` varchar(20) NOT NULL,
  `year` varchar(20) NOT NULL,
  `staus_orgin` varchar(40) NOT NULL,
  `location` varchar(80) NOT NULL,
  `chart_month` varchar(50) NOT NULL,
  `chart_year` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25622 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `monthly_account`
--

DROP TABLE IF EXISTS `monthly_account`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `monthly_account` (
  `id` int(11) NOT NULL,
  `month` varchar(30) NOT NULL,
  `income` int(11) NOT NULL,
  `expenses` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `monthly_charges`
--

DROP TABLE IF EXISTS `monthly_charges`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `monthly_charges` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `paid` int(11) NOT NULL,
  `to_be` int(11) NOT NULL,
  `location` varchar(100) NOT NULL,
  `date_insert` varchar(20) NOT NULL,
  `no_to_be` int(11) NOT NULL,
  `no_paid` int(11) NOT NULL,
  `no_due` int(11) NOT NULL,
  `sort` int(11) NOT NULL,
  `username` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `date` (`location`),
  KEY `date2` (`date_insert`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=621 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `not_account`
--

DROP TABLE IF EXISTS `not_account`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `not_account` (
  `id` int(11) NOT NULL,
  `acc_id` int(11) NOT NULL,
  `status` varchar(80) NOT NULL,
  `remain` varchar(80) NOT NULL,
  `name` varchar(100) NOT NULL,
  `amount` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `date` varchar(50) NOT NULL,
  `month` varchar(50) NOT NULL,
  `year` varchar(50) NOT NULL,
  `clear` varchar(500) NOT NULL,
  `date_full` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `notification`
--

DROP TABLE IF EXISTS `notification`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notification` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `s_name` varchar(30) NOT NULL,
  `r_name` varchar(30) NOT NULL,
  `s_id` varchar(30) NOT NULL,
  `date` varchar(30) NOT NULL,
  `uploader` varchar(30) NOT NULL,
  `notification` varchar(30) NOT NULL,
  `code` varchar(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `topay` int(11) NOT NULL,
  `payed` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `status` varchar(30) NOT NULL,
  `username` varchar(50) NOT NULL,
  `deleted` varchar(30) NOT NULL,
  `deleted_date` varchar(30) NOT NULL,
  `update_v` varchar(200) NOT NULL,
  `updated` varchar(20) NOT NULL,
  `l_status` varchar(50) NOT NULL,
  `what_changed` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11945 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `notification_manifest`
--

DROP TABLE IF EXISTS `notification_manifest`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notification_manifest` (
  `id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `office_expenses`
--

DROP TABLE IF EXISTS `office_expenses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `office_expenses` (
  `id` int(11) NOT NULL,
  `opening_stock` int(11) NOT NULL,
  `issuer` varchar(30) NOT NULL,
  `date` varchar(30) NOT NULL,
  `time` varchar(30) NOT NULL,
  `authorizer` text NOT NULL,
  `open_date` varchar(20) NOT NULL,
  `stock_no` int(11) NOT NULL,
  `ammount_remain` int(11) NOT NULL,
  `status` varchar(20) NOT NULL,
  `percentage` int(11) NOT NULL,
  `color` varchar(300) NOT NULL,
  `amount_dollar` decimal(11,2) NOT NULL,
  `account` varchar(10) NOT NULL,
  `day` varchar(20) NOT NULL,
  `month` varchar(20) NOT NULL,
  `year` varchar(20) NOT NULL,
  `bank` varchar(60) NOT NULL,
  `check_no` varchar(100) NOT NULL,
  `check_to` varchar(60) NOT NULL,
  `vehicle` varchar(78) NOT NULL,
  `name` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page` varchar(200) NOT NULL,
  `page_name` varchar(200) NOT NULL,
  `detail` text NOT NULL,
  `image` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `parcel_info`
--

DROP TABLE IF EXISTS `parcel_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `parcel_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `s_name` varchar(30) DEFAULT NULL,
  `s_surname` int(11) DEFAULT NULL,
  `s_no` varchar(15) DEFAULT NULL,
  `r_name` varchar(30) DEFAULT NULL,
  `r_surname` varchar(30) DEFAULT NULL,
  `r_no` varchar(15) DEFAULT NULL,
  `account` varchar(30) DEFAULT NULL,
  `date` varchar(30) DEFAULT NULL,
  `detail` text DEFAULT NULL,
  `status` varchar(15) DEFAULT NULL,
  `amount` float NOT NULL,
  `code` varchar(30) DEFAULT NULL,
  `payment_status` float DEFAULT NULL,
  `rec_status` varchar(10) DEFAULT NULL,
  `question` varchar(50) NOT NULL,
  `answer` float NOT NULL,
  `store` varchar(11) DEFAULT NULL,
  `due` float NOT NULL,
  `edited` varchar(20) DEFAULT NULL,
  `scaned` int(11) NOT NULL,
  `date_insert` varchar(30) DEFAULT NULL,
  `amount_remain` float NOT NULL,
  `time` varchar(10) DEFAULT NULL,
  `uploader` varchar(45) DEFAULT NULL,
  `username` varchar(30) DEFAULT NULL,
  `buss` varchar(40) DEFAULT NULL,
  `trip_date` varchar(30) DEFAULT NULL,
  `payed` float NOT NULL,
  `to_be` float NOT NULL,
  `month` varchar(20) DEFAULT NULL,
  `updated` varchar(20) DEFAULT NULL,
  `initial` varchar(200) DEFAULT NULL,
  `country` varchar(200) DEFAULT NULL,
  `code_extra` int(11) DEFAULT NULL,
  `loan` int(11) DEFAULT NULL,
  `loan_update` varchar(40) DEFAULT NULL,
  `cleared` varchar(40) DEFAULT NULL,
  `print_code` varchar(100) DEFAULT NULL,
  `open` varchar(40) NOT NULL,
  `weight_name` varchar(20) DEFAULT NULL,
  `weight_value` int(11) DEFAULT NULL,
  `lugg_value` int(11) NOT NULL,
  `payed_remain` float NOT NULL,
  `to_be_remain` float NOT NULL,
  `quantity_remain` int(11) NOT NULL,
  `printed_time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `parcel_index` (`code`,`store`,`scaned`,`question`),
  KEY `everything_and_the_kitchen_sink_ndx` (`amount`,`payed`,`to_be`)
) ENGINE=InnoDB AUTO_INCREMENT=391279 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `petty_cash`
--

DROP TABLE IF EXISTS `petty_cash`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `petty_cash` (
  `id` int(11) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `date` varchar(15) NOT NULL,
  `status` varchar(100) NOT NULL,
  `detail` text NOT NULL,
  `r_date` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pety`
--

DROP TABLE IF EXISTS `pety`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pety` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `date` varchar(20) NOT NULL,
  `status` varchar(29) NOT NULL,
  `detail` text NOT NULL,
  `date_return` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `qrcodes`
--

DROP TABLE IF EXISTS `qrcodes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `qrcodes` (
  `id` int(11) NOT NULL,
  `qrUsername` varchar(250) NOT NULL,
  `qrContent` varchar(250) NOT NULL,
  `qrImg` varchar(250) NOT NULL,
  `qrlink` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `level` varchar(60) NOT NULL,
  `file` varchar(100) NOT NULL,
  `username` varchar(200) NOT NULL,
  `category` varchar(200) NOT NULL,
  `image` varchar(800) NOT NULL,
  `arrangment` int(11) NOT NULL,
  `branch` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `roles_assiged`
--

DROP TABLE IF EXISTS `roles_assiged`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles_assiged` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `level` varchar(200) NOT NULL,
  `file` varchar(200) NOT NULL,
  `username` varchar(200) NOT NULL,
  `category` varchar(200) NOT NULL,
  `arrangment` int(11) NOT NULL,
  `sub_url` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1478 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `routes`
--

DROP TABLE IF EXISTS `routes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `routes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `start_route` varchar(80) DEFAULT NULL,
  `end_route` varchar(80) DEFAULT NULL,
  `fair_price` int(11) DEFAULT NULL,
  `fair_price_international` int(11) DEFAULT NULL,
  `sub_route_id` varchar(78) DEFAULT NULL,
  `route_name` varchar(100) DEFAULT NULL,
  `route_id` int(11) DEFAULT NULL,
  `arraval` varchar(10) DEFAULT NULL,
  `departure` varchar(10) DEFAULT NULL,
  `detail` text DEFAULT NULL,
  `currency` varchar(100) DEFAULT NULL,
  `currency_symbol` varchar(100) DEFAULT NULL,
  `active_date` int(11) DEFAULT NULL,
  `route_full_name` varchar(200) DEFAULT NULL,
  `initial` varchar(20) DEFAULT NULL,
  `contact_1` varchar(100) DEFAULT NULL,
  `contact_2` varchar(100) DEFAULT NULL,
  `adress_1` varchar(100) DEFAULT NULL,
  `adress_2` varchar(100) DEFAULT NULL,
  `term_1_new1` text DEFAULT NULL,
  `term_2_new` text DEFAULT NULL,
  `tranist_route_name` varchar(200) DEFAULT NULL,
  `tranist_route_id` int(11) DEFAULT NULL,
  `to_be_paid_currency` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `store`
--

DROP TABLE IF EXISTS `store`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `store` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `to_be` int(11) NOT NULL,
  `paid` int(11) NOT NULL,
  `location` varchar(800) NOT NULL,
  `username` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sub_category`
--

DROP TABLE IF EXISTS `sub_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sub_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `url` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_sample`
--

DROP TABLE IF EXISTS `tbl_sample`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_sample` (
  `id` int(11) NOT NULL,
  `first_name` varchar(250) NOT NULL,
  `last_name` varchar(250) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tranist`
--

DROP TABLE IF EXISTS `tranist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tranist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `primary_route` varchar(200) DEFAULT NULL,
  `primary_route_id` int(11) DEFAULT NULL,
  `secondary_route` varchar(200) DEFAULT NULL,
  `secondary_route_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(78) NOT NULL,
  `password` varchar(78) NOT NULL,
  `firstname` varchar(80) NOT NULL,
  `surname` varchar(70) NOT NULL,
  `role` varchar(80) NOT NULL,
  `location` varchar(80) NOT NULL,
  `email` varchar(100) NOT NULL,
  `number` varchar(100) NOT NULL,
  `status` varchar(30) NOT NULL,
  `button_name` varchar(70) NOT NULL,
  `button_icon` varchar(100) NOT NULL,
  `button_class` varchar(100) NOT NULL,
  `image` varchar(600) NOT NULL,
  `fullname` varchar(200) NOT NULL,
  `level` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `value_selector`
--

DROP TABLE IF EXISTS `value_selector`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `value_selector` (
  `id` int(11) NOT NULL,
  `date` varchar(40) NOT NULL,
  `vehicle` varchar(40) NOT NULL,
  `name` varchar(40) NOT NULL,
  `amount` varchar(40) NOT NULL,
  `detail` text NOT NULL,
  `select_field` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `vehicle_exp`
--

DROP TABLE IF EXISTS `vehicle_exp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vehicle_exp` (
  `id` int(11) NOT NULL,
  `name` varchar(1000) NOT NULL,
  `date` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `vehicle_expenses`
--

DROP TABLE IF EXISTS `vehicle_expenses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vehicle_expenses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `date` varchar(20) NOT NULL,
  `To_pay` int(11) NOT NULL,
  `pay` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `expenses` int(11) NOT NULL,
  `income` int(11) NOT NULL,
  `location` varchar(100) NOT NULL,
  `to_pay_dollar` decimal(11,2) NOT NULL,
  `pay_dollar` decimal(11,2) NOT NULL,
  `amount_dollar` decimal(11,2) NOT NULL,
  `gross_profit` int(11) NOT NULL,
  `gross_profit_dolllar` decimal(11,2) NOT NULL,
  `to_be_gross` int(11) NOT NULL,
  `paid_gross` decimal(11,2) NOT NULL,
  `to_be_dollar_gross` decimal(11,2) NOT NULL,
  `paid_dollar_gross` decimal(11,2) NOT NULL,
  `profit` int(11) NOT NULL,
  `expneses_away` int(11) NOT NULL,
  `expenses_away_dollar` int(11) NOT NULL,
  `income_away` int(11) NOT NULL,
  `income_away_dollar` int(11) NOT NULL,
  `lock_v` varchar(500) NOT NULL,
  `locked_by` varchar(100) NOT NULL,
  `passcode` varchar(30) NOT NULL,
  `day` varchar(20) NOT NULL,
  `month` varchar(20) NOT NULL,
  `year` varchar(20) NOT NULL,
  `fleet` varchar(70) NOT NULL,
  `fleet_expenses` varchar(60) NOT NULL,
  `checked` varchar(1000) NOT NULL,
  `exp_from` varchar(40) NOT NULL,
  `analyse_date` varchar(20) NOT NULL,
  `viewd` varchar(500) NOT NULL,
  `open_date` varchar(300) NOT NULL,
  `date_sort` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2559 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `vehicle_name`
--

DROP TABLE IF EXISTS `vehicle_name`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vehicle_name` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(30) NOT NULL,
  `Driver` varchar(100) NOT NULL,
  `Conductor` varchar(30) NOT NULL,
  `Route` varchar(100) NOT NULL,
  `luggages_no` int(11) NOT NULL,
  `to_pay` float NOT NULL,
  `payed` float NOT NULL,
  `due` float NOT NULL,
  `status` varchar(10) NOT NULL,
  `date` text NOT NULL,
  `passcode` varchar(100) NOT NULL,
  `creaded_by` varchar(100) NOT NULL,
  `locked_by` varchar(100) NOT NULL,
  `l_payed` int(11) NOT NULL,
  `l_to_be` int(11) NOT NULL,
  `l_ammount` int(11) NOT NULL,
  `img` text NOT NULL,
  `disable` varchar(100) NOT NULL,
  `disable2` varchar(60) NOT NULL,
  `updated` varchar(20) NOT NULL,
  `id2` int(11) NOT NULL,
  `changed` text NOT NULL,
  `changed_user` varchar(200) NOT NULL,
  `change_time` varchar(40) NOT NULL,
  `code` varchar(20) NOT NULL,
  `remark_test` text NOT NULL,
  `remark_html` text NOT NULL,
  `username_remark` varchar(100) NOT NULL,
  `currency` varchar(20) NOT NULL,
  `routes` text NOT NULL,
  `changes` text NOT NULL,
  `manifest_routes` text NOT NULL,
  `users` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `vehicl_index` (`Name`)
) ENGINE=InnoDB AUTO_INCREMENT=5741 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `vehicles`
--

DROP TABLE IF EXISTS `vehicles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vehicles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `driver` varchar(100) NOT NULL,
  `conductor` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=97 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-07-26  5:35:12
