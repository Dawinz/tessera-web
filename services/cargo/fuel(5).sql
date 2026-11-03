-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 21, 2021 at 04:25 PM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fuel`
--

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `location` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`id`, `name`, `location`) VALUES
(1, 'asdf', 'Tiper'),
(4, 'asdf', 'Mogas');

-- --------------------------------------------------------

--
-- Table structure for table `deployment`
--

CREATE TABLE `deployment` (
  `id` int(11) NOT NULL,
  `id_no` varchar(100) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `location` varchar(100) NOT NULL,
  `1` text NOT NULL,
  `2` text NOT NULL,
  `3` text NOT NULL,
  `4` text NOT NULL,
  `5` text NOT NULL,
  `6` text NOT NULL,
  `7` text NOT NULL,
  `8` text NOT NULL,
  `9` text NOT NULL,
  `10` text NOT NULL,
  `11` text NOT NULL,
  `12` text NOT NULL,
  `13` text NOT NULL,
  `14` text NOT NULL,
  `15` text NOT NULL,
  `16` text NOT NULL,
  `17` text NOT NULL,
  `18` text NOT NULL,
  `19` text NOT NULL,
  `20` text NOT NULL,
  `21` text NOT NULL,
  `22` text NOT NULL,
  `23` text NOT NULL,
  `24` text NOT NULL,
  `25` text NOT NULL,
  `26` text NOT NULL,
  `27` text NOT NULL,
  `28` text NOT NULL,
  `29` text NOT NULL,
  `30` text NOT NULL,
  `31` text NOT NULL,
  `month` varchar(30) NOT NULL,
  `uploader` varchar(40) NOT NULL,
  `uploader_name` varchar(80) NOT NULL,
  `deployment_date` varchar(30) NOT NULL,
  `date` varchar(30) NOT NULL,
  `time` varchar(30) NOT NULL,
  `c_time` varchar(30) NOT NULL,
  `times` varchar(100) NOT NULL,
  `24hrs` int(11) NOT NULL,
  `lock_d` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `deployment`
--

INSERT INTO `deployment` (`id`, `id_no`, `fullname`, `location`, `1`, `2`, `3`, `4`, `5`, `6`, `7`, `8`, `9`, `10`, `11`, `12`, `13`, `14`, `15`, `16`, `17`, `18`, `19`, `20`, `21`, `22`, `23`, `24`, `25`, `26`, `27`, `28`, `29`, `30`, `31`, `month`, `uploader`, `uploader_name`, `deployment_date`, `date`, `time`, `c_time`, `times`, `24hrs`, `lock_d`) VALUES
(1, '25', 'abdlaziz iddi', 'Tiper', '', '<img src=\"img/check2.png\"/>', '', '', '', '<img src=\"img/pass.png\"/>', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '08/2021', '', '', ' 08/02/2021', '', '', '', '', 18, ''),
(2, '475', 'juma  1991587121', 'Tiper', '', '<img src=\"img/delete.png\"/>', '', '<img src=\"img/check2.png\"/>', '', '<img src=\"img/return.png\"/>', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '08/2021', '', '', '08/02/2021', '', '', '', '', 18, ''),
(3, '457', 'chumong juma', 'Mogas', '', '<img src=\"img/check2.png\"/>', '', '', '', '<img src=\"img/return.png\"/>', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '08/2021', '', '', '08/02/2021', '', '', '', '', 18, ''),
(4, '530', 'jackson  kamei', 'Mogas', '', '<img src=\"img/check2.png\"/>', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '08/2021', '', '', '08/02/2021', '', '', '', '', 18, ''),
(5, '532', 'knya kamei', 'Hass', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '08/2021', '', '', '08/02/2021', '', '', '', '', 18, ''),
(6, '534', 'bakari  kambarage', 'Hass', '', '<img src=\"img/check2.png\"/>', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '08/2021', '', '', '08/02/2021', '', '', '', '', 18, ''),
(7, '533', 'paulo bakari', 'Tiper', '', '<img src=\"img/check2.png\"/>', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '08/2021', '', '', '08/02/2021', '', '', '', '', 18, ''),
(8, '534', 'juma bakari', 'Tiper', '', '<img src=\"img/check2.png\"/>', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '08/2021', '', '', '08/02/2021', '', '', '', '', 18, ''),
(9, '577', 'hamza kaka', 'Mogas', '', '<img src=\"img/delete.png\"/>', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '08/2021', '', '', '08/02/2021', '', '', '', '', 18, ''),
(10, '214', 'kalisa hzi', 'Tiper', '', '<img src=\"img/delete.png\"/>', '', '<img src=\"img/check2.png\"/>', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '08/2021', '', '', '08/02/2021', '', '', '', '', 18, '');

-- --------------------------------------------------------

--
-- Table structure for table `deployment_view`
--

CREATE TABLE `deployment_view` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `location` varchar(30) NOT NULL,
  `date` varchar(30) NOT NULL,
  `day_time` varchar(30) NOT NULL,
  `username` varchar(30) NOT NULL,
  `checker` varchar(30) NOT NULL,
  `time_checked` varchar(30) NOT NULL,
  `status` varchar(30) NOT NULL,
  `id_no` varchar(30) NOT NULL,
  `day` varchar(30) NOT NULL,
  `month` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `deployment_view`
--

INSERT INTO `deployment_view` (`id`, `name`, `location`, `date`, `day_time`, `username`, `checker`, `time_checked`, `status`, `id_no`, `day`, `month`) VALUES
(1, 'juma  1991587121', 'Tiper', '02/08/2021', 'Night', '', '', '06:20 PM', 'Present', '475', '', ''),
(2, 'kalisa hzi', 'Tiper', '02/08/2021', 'Night', '', '', '06:22 PM', 'Present', '214', '', ''),
(3, 'abdlaziz iddi', 'Tiper', '02/08/2021', 'Night', '', '', '06:23 PM', 'Absent', '25', '', ''),
(4, 'juma bakari', 'Tiper', '02/08/2021', 'Night', '', '', '07:38 PM', 'Present', '534', '', ''),
(5, 'paulo bakari', 'Tiper', '02/08/2021', 'Night', '', '', '07:41 PM', 'Present', '533', '', ''),
(6, 'chumong juma', 'Mogas', '02/08/2021', 'Night', '', '', '07:44 PM', 'Present', '457', '', ''),
(7, 'hamza kaka', 'Mogas', '02/08/2021', 'Night', '', '', '07:44 PM', 'Absent', '577', '', ''),
(8, 'jackson  kamei', 'Mogas', '02/08/2021', 'Night', '', '', '07:46 PM', 'Present', '530', '', ''),
(9, 'juma  1991587121', 'Tiper', '04/08/2021', 'Noon', '', '', '10:10 AM', 'Present', '475', '', ''),
(10, 'kalisa hzi', 'Tiper', '04/08/2021', 'Noon', '', '', '11:12 AM', 'Present', '214', '4', '08/2021');

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `id` int(11) NOT NULL,
  `firstname` varchar(40) CHARACTER SET latin1 NOT NULL,
  `lastname` varchar(40) CHARACTER SET latin1 NOT NULL,
  `surname` varchar(40) CHARACTER SET latin1 NOT NULL,
  `date` varchar(30) CHARACTER SET latin1 NOT NULL,
  `no` varchar(20) NOT NULL,
  `alt_no` varchar(20) NOT NULL,
  `email` varchar(30) CHARACTER SET latin1 NOT NULL,
  `nssf` varchar(30) CHARACTER SET latin1 NOT NULL,
  `bank` varchar(20) NOT NULL,
  `id_no` varchar(20) NOT NULL,
  `post` varchar(30) CHARACTER SET latin1 NOT NULL,
  `grade` varchar(30) CHARACTER SET latin1 NOT NULL,
  `medical` text CHARACTER SET latin1 NOT NULL,
  `other_info` varchar(30) CHARACTER SET latin1 NOT NULL,
  `date_join` varchar(30) CHARACTER SET latin1 NOT NULL,
  `status` varchar(30) CHARACTER SET latin1 NOT NULL,
  `bios` text CHARACTER SET latin1 NOT NULL,
  `date_insert` varchar(30) CHARACTER SET latin1 NOT NULL,
  `time` varchar(10) CHARACTER SET latin1 NOT NULL,
  `username` varchar(20) CHARACTER SET latin1 NOT NULL,
  `uploader_name` varchar(20) CHARACTER SET latin1 NOT NULL,
  `image` text CHARACTER SET latin1 NOT NULL,
  `gender` varchar(20) NOT NULL,
  `id_card` int(11) NOT NULL,
  `rank` varchar(40) NOT NULL,
  `check_date` varchar(30) NOT NULL,
  `status_presence` varchar(50) NOT NULL,
  `availability` varchar(50) NOT NULL,
  `checked_status` varchar(30) NOT NULL,
  `leave_pass` varchar(30) NOT NULL,
  `availablity` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`id`, `firstname`, `lastname`, `surname`, `date`, `no`, `alt_no`, `email`, `nssf`, `bank`, `id_no`, `post`, `grade`, `medical`, `other_info`, `date_join`, `status`, `bios`, `date_insert`, `time`, `username`, `uploader_name`, `image`, `gender`, `id_card`, `rank`, `check_date`, `status_presence`, `availability`, `checked_status`, `leave_pass`, `availablity`) VALUES
(10, 'abdlaziz', 'gosageg', 'iddi', '04/02/2020', '255725554', '5855', 'abdulazizgossage909@gmail.com', '546', '5', '25', 'Tiper', 'Grade 3', 'asdf', 'asdf', '05/07/2021', '2', 'PHA+dGhzIGVuIHNmYXM8L3A+', ' 07/10/2021', ' 03:01 PM', '', '', 'uploads/134755140360e98c2aaf48f.jpg', '', 0, '', '06/08/2021', 'available', 'present', 'not', 'Pass', ''),
(54, 'juma ', 'kaka ', '1991587121', '06/07/2021', '0715511420', '0715511420', 'abdulazizgossage909@gmail.com', '4574112', '014547855', '475', 'Tiper', 'Grade 3', 'good', 'other', '05/07/2021', '', 'PGgyIHN0eWxlPSJtYXJnaW4tYm90dG9tOiAxMHB4OyBwYWRkaW5nOiAwcHg7IGZvbnQtZmFtaWx5OiBEYXVwaGluUGxhaW47IGZvbnQtc2l6ZTogMjRweDsgbGluZS1oZWlnaHQ6IDI0cHg7IGNvbG9yOiByZ2IoMCwgMCwgMCk7Ij5XaGF0IGlzIExvcmVtIElwc3VtPzwvaDI+PHAgc3R5bGU9Im1hcmdpbi1ib3R0b206IDE1cHg7IHBhZGRpbmc6IDBweDsgdGV4dC1hbGlnbjoganVzdGlmeTsgY29sb3I6IHJnYigwLCAwLCAwKTsgZm9udC1mYW1pbHk6ICZxdW90O09wZW4gU2FucyZxdW90OywgQXJpYWwsIHNhbnMtc2VyaWY7IGZvbnQtc2l6ZTogMTRweDsiPjxzdHJvbmcgc3R5bGU9Im1hcmdpbjogMHB4OyBwYWRkaW5nOiAwcHg7Ij5Mb3JlbSBJcHN1bTwvc3Ryb25nPiZuYnNwO2lzIHNpbXBseSBkdW1teSB0ZXh0IG9mIHRoZSBwcmludGluZyBhbmQgdHlwZXNldHRpbmcgaW5kdXN0cnkuIExvcmVtIElwc3VtIGhhcyBiZWVuIHRoZSBpbmR1c3RyeSdzIHN0YW5kYXJkIGR1bW15IHRleHQgZXZlciBzaW5jZSB0aGUgMTUwMHMsIHdoZW4gYW4gdW5rbm93biBwcmludGVyIHRvb2sgYSBnYWxsZXkgb2YgdHlwZSBhbmQgc2NyYW1ibGVkIGl0IHRvIG1ha2UgYSB0eXBlIHNwZWNpbWVuIGJvb2suIEl0IGhhcyBzdXJ2aXZlZCBub3Qgb25seSBmaXZlIGNlbnR1cmllcywgYnV0IGFsc28gdGhlIGxlYXAgaW50byBlbGVjdHJvbmljIHR5cGVzZXR0aW5nLCByZW1haW5pbmcgZXNzZW50aWFsbHkgdW5jaGFuZ2VkLiBJdCB3YXMgcG9wdWxhcmlzZWQgaW4gdGhlIDE5NjBzIHdpdGggdGhlIHJlbGVhc2Ugb2YgTGV0cmFzZXQgc2hlZXRzIGNvbnRhaW5pbmcgTG9yZW0gSXBzdW0gcGFzc2FnZXMsIGFuZCBtb3JlIHJlY2VudGx5IHdpdGggZGVza3RvcCBwdWJsaXNoaW5nIHNvZnR3YXJlIGxpa2UgQWxkdXMgUGFnZU1ha2VyIGluY2x1ZGluZyB2ZXJzaW9ucyBvZiBMb3JlbSBJcHN1bS48L3A+', ' 07/14/2021', ' 06:51 PM', '', '', '../uploads/44031127160ef07ed440e1.jpg', '1', 35, '2', '06/08/2021', 'available', 'present', 'not', 'Leave', ''),
(55, 'chumong', 'bakari', 'juma', '16/07/2021', '0715511420', '0715511420', 'abdulazizgossage909@gmail.com', '4574112', '011141112', '457', 'Mogas', 'Grade 3', 'good', 'other', '05/07/2021', 'Single', 'PGgyIHN0eWxlPSJtYXJnaW4tYm90dG9tOiAxMHB4OyBwYWRkaW5nOiAwcHg7IGZvbnQtZmFtaWx5OiBEYXVwaGluUGxhaW47IGZvbnQtc2l6ZTogMjRweDsgbGluZS1oZWlnaHQ6IDI0cHg7IGNvbG9yOiByZ2IoMCwgMCwgMCk7Ij5XaGF0IGlzIExvcmVtIElwc3VtPzwvaDI+PHAgc3R5bGU9Im1hcmdpbi1ib3R0b206IDE1cHg7IHBhZGRpbmc6IDBweDsgdGV4dC1hbGlnbjoganVzdGlmeTsgY29sb3I6IHJnYigwLCAwLCAwKTsgZm9udC1mYW1pbHk6ICZxdW90O09wZW4gU2FucyZxdW90OywgQXJpYWwsIHNhbnMtc2VyaWY7IGZvbnQtc2l6ZTogMTRweDsiPjxzdHJvbmcgc3R5bGU9Im1hcmdpbjogMHB4OyBwYWRkaW5nOiAwcHg7Ij5Mb3JlbSBJcHN1bTwvc3Ryb25nPiZuYnNwO2lzIHNpbXBseSBkdW1teSB0ZXh0IG9mIHRoZSBwcmludGluZyBhbmQgdHlwZXNldHRpbmcgaW5kdXN0cnkuIExvcmVtIElwc3VtIGhhcyBiZWVuIHRoZSBpbmR1c3RyeSdzIHN0YW5kYXJkIGR1bW15IHRleHQgZXZlciBzaW5jZSB0aGUgMTUwMHMsIHdoZW4gYW4gdW5rbm93biBwcmludGVyIHRvb2sgYSBnYWxsZXkgb2YgdHlwZSBhbmQgc2NyYW1ibGVkIGl0IHRvIG1ha2UgYSB0eXBlIHNwZWNpbWVuIGJvb2suIEl0IGhhcyBzdXJ2aXZlZCBub3Qgb25seSBmaXZlIGNlbnR1cmllcywgYnV0IGFsc28gdGhlIGxlYXAgaW50byBlbGVjdHJvbmljIHR5cGVzZXR0aW5nLCByZW1haW5pbmcgZXNzZW50aWFsbHkgdW5jaGFuZ2VkLiBJdCB3YXMgcG9wdWxhcmlzZWQgaW4gdGhlIDE5NjBzIHdpdGggdGhlIHJlbGVhc2Ugb2YgTGV0cmFzZXQgc2hlZXRzIGNvbnRhaW5pbmcgTG9yZW0gSXBzdW0gcGFzc2FnZXMsIGFuZCBtb3JlIHJlY2VudGx5IHdpdGggZGVza3RvcCBwdWJsaXNoaW5nIHNvZnR3YXJlIGxpa2UgQWxkdXMgUGFnZU1ha2VyIGluY2x1ZGluZyB2ZXJzaW9ucyBvZiBMb3JlbSBJcHN1bS48L3A+', ' 07/14/2021', ' 06:58 PM', '', '', '../uploads/39276080960ef09c336c32.jpg', 'Male', 1575122, '2', '06/08/2021', 'available', 'present', 'not', 'Leave', ''),
(56, 'jackson ', 'junior ', 'kamei', '13/07/2021', '255715511420', '25565741', 'abdulazizgossage909@gmail.com', '45122', '014545522', '530', 'Mogas', 'Grade 3', 'good', 'sdafdasf', '05/07/2021', 'Married', 'PHA+dGhlIG5ldyBib3N0b248L3A+', ' 07/14/2021', ' 07:04 PM', '', '', '../uploads/44095428060ef0b14ef541.jpg', 'Female', 19914571, '', '06/08/2021', 'available', 'present', 'not', '', ''),
(57, 'knya', 'kazi', 'kamei', '13/07/2021', '255715511420', '25565741', 'abdulazizgossage909@gmail.com', '45122', '014545522', '532', 'Hass', 'Grade 3', 'good', 'sdafdasf', '05/07/2021', 'Single', 'PHA+dGhlIG5ldyBib3N0b248L3A+', ' 07/14/2021', ' 07:05 PM', '', '', '../uploads/40556337360ef0b3ac1437.jpg', 'Male', 19914571, '2', '06/08/2021', 'available', 'present', 'not', '', ''),
(58, 'bakari ', 'njoma', 'kambarage', '13/07/2021', '255715511420', '25565741', 'abdulazizgossage909@gmail.com', '45122', '014545522', '534', 'Hass', 'Grade 3', 'good', 'sdafdasf', '05/07/2021', 'Single', 'PHA+dGhlIG5ldyBib3N0b248L3A+', ' 07/14/2021', ' 07:07 PM', '', '', '../uploads/81240119660ef0ba62e085.jpg', 'Male', 29914571, '2', '06/08/2021', 'available', 'present', 'not', '', ''),
(59, 'paulo', 'noni', 'bakari', '13/07/2021', '255715511420', '25565741', 'abdulazizgossage909@gmail.com', '45122', '014545522', '533', 'Tiper', 'Grade 3', 'good', 'sdafdasf', '05/07/2021', 'Single', 'PHA+dGhlIG5ldyBib3N0b248L3A+', ' 07/14/2021', ' 07:07 PM', '', '', 'uploads/32721185660ef0bbdcd729.jpg', 'Male', 29914571, '2', '06/08/2021', 'available', 'present', 'not', '', ''),
(60, 'juma', 'uxa', 'bakari', '13/07/2021', '255715511420', '25565741', 'abdulazizgossage909@gmail.com', '45122', '014545522', '534', 'Tiper', 'Grade 3', 'good', 'sdafdasf', '05/07/2021', 'Single', 'PHA+dGhlIG5ldyBib3N0b248L3A+', ' 07/14/2021', ' 07:21 PM', '', '', 'uploads/147765504660ef0f13be937.jpg', 'Male', 29914571, '2', '06/08/2021', 'available', 'present', 'not', '', ''),
(61, 'hamza', 'maximum', 'kaka', '13/07/2021', '255715511420', '25565741', 'abdulazizgossage909@gmail.com', '45122', '014545522', '577', 'Mogas', 'Grade 3', 'good', 'sdafdasf', '05/07/2021', 'Single', 'PHA+dGhlIG5ldyBib3N0b25hZHNmPC9wPg==', ' 07/14/2021', ' 07:23 PM', '', '', 'uploads/118772408560ef0f79ef11d.jpg', 'Male', 29914571, '2', '06/08/2021', 'available', 'present', 'not', '', ''),
(62, 'kalisa', 'salehi', 'hzi', '13/07/2021', '255715511420', '25565741', 'abdulazizgossage909@gmail.com', '45122', '014545522', '214', 'Tiper', 'Grade 3', 'good', 'sdafdasf', '05/07/2021', 'Single', 'PHA+dGhlIG5ldyBib3N0b25hZHNmPC9wPg==', ' 07/14/2021', ' 07:26 PM', '', '', '../uploads/97627777560ef103f81d88.jpg', 'Male', 29914571, '2', '06/08/2021', 'available', 'present', 'not', '', ''),
(63, 'Mtambo ', 'leo', 'bakari', '19/08/2021', '345', '111', 'abduasdf@fna,cin', '333', '1111', '09/08/2021', 'Mogas', 'Grade 3', 'noto', 'non', '09/08/2021', 'Single', 'PHA+bmV3IGJvc3Rvbjxicj48L3A+', ' 08/09/2021', ' 02:01 PM', '', '', '../uploads/51523530361110b23399b6.jpg', 'Male', 1234555, 'Superviour', '', '', '', '', '', ''),
(64, 'Luis', 'Dismas', 'Kilonzo', '10/08/2021', '255715511420', '255765113711', 'abdulazizgossage@yahoo.com', '5734554', '8837633636', '676', 'Tiper', 'Grade', 'nono', 'non', '16/08/2021', 'Single', 'PGgyPldoYXQgaXMgTG9yZW0gSXBzdW0/PC9oMj48cD4NCjxzdHJvbmc+TG9yZW0gSXBzdW08L3N0cm9uZz4gaXMgc2ltcGx5IGR1bW15IHRleHQgb2YgdGhlIHByaW50aW5nIGFuZA0KIHR5cGVzZXR0aW5nIGluZHVzdHJ5LiBMb3JlbSBJcHN1bSBoYXMgYmVlbiB0aGUgaW5kdXN0cnkncyBzdGFuZGFyZCANCmR1bW15IHRleHQgZXZlciBzaW5jZSB0aGUgMTUwMHMsIHdoZW4gYW4gdW5rbm93biBwcmludGVyIHRvb2sgYSBnYWxsZXkgDQpvZiB0eXBlIGFuZCBzY3JhbWJsZWQgaXQgdG8gbWFrZSBhIHR5cGUgc3BlY2ltZW4gYm9vay4gSXQgaGFzIHN1cnZpdmVkIA0Kbm90IG9ubHkgZml2ZSBjZW50dXJpZXMsIGJ1dCBhbHNvIHRoZSBsZWFwIGludG8gZWxlY3Ryb25pYyB0eXBlc2V0dGluZywgDQpyZW1haW5pbmcgZXNzZW50aWFsbHkgdW5jaGFuZ2VkLiBJdCB3YXMgcG9wdWxhcmlzZWQgaW4gdGhlIDE5NjBzIHdpdGggDQp0aGUgcmVsZWFzZSBvZiBMZXRyYXNldCBzaGVldHMgY29udGFpbmluZyBMb3JlbSBJcHN1bSBwYXNzYWdlcywgYW5kIG1vcmUNCiByZWNlbnRseSB3aXRoIGRlc2t0b3AgcHVibGlzaGluZyBzb2Z0d2FyZSBsaWtlIEFsZHVzIFBhZ2VNYWtlciANCmluY2x1ZGluZyB2ZXJzaW9ucyBvZiBMb3JlbSBJcHN1bTxicj48L3A+', ' 08/10/2021', ' 05:42 PM', '', '', '../uploads/4970011696112905b46377.jpg', 'Male', 139874211, 'Controller', '', '', '', '', '', 'Active'),
(65, 'Luis', 'Dismas', 'Kilonzo', '10/08/2021', '255715511420', '255765113711', 'abdulazizgossage@yahoo.com', '5734554', '8837633636', '676', 'Tiper', 'Grade', 'nono', 'non', '16/08/2021', 'Single', 'PGgyPldoYXQgaXMgTG9yZW0gSXBzdW0/PC9oMj48cD4NCjxzdHJvbmc+TG9yZW0gSXBzdW08L3N0cm9uZz4gaXMgc2ltcGx5IGR1bW15IHRleHQgb2YgdGhlIHByaW50aW5nIGFuZA0KIHR5cGVzZXR0aW5nIGluZHVzdHJ5LiBMb3JlbSBJcHN1bSBoYXMgYmVlbiB0aGUgaW5kdXN0cnkncyBzdGFuZGFyZCANCmR1bW15IHRleHQgZXZlciBzaW5jZSB0aGUgMTUwMHMsIHdoZW4gYW4gdW5rbm93biBwcmludGVyIHRvb2sgYSBnYWxsZXkgDQpvZiB0eXBlIGFuZCBzY3JhbWJsZWQgaXQgdG8gbWFrZSBhIHR5cGUgc3BlY2ltZW4gYm9vay4gSXQgaGFzIHN1cnZpdmVkIA0Kbm90IG9ubHkgZml2ZSBjZW50dXJpZXMsIGJ1dCBhbHNvIHRoZSBsZWFwIGludG8gZWxlY3Ryb25pYyB0eXBlc2V0dGluZywgDQpyZW1haW5pbmcgZXNzZW50aWFsbHkgdW5jaGFuZ2VkLiBJdCB3YXMgcG9wdWxhcmlzZWQgaW4gdGhlIDE5NjBzIHdpdGggDQp0aGUgcmVsZWFzZSBvZiBMZXRyYXNldCBzaGVldHMgY29udGFpbmluZyBMb3JlbSBJcHN1bSBwYXNzYWdlcywgYW5kIG1vcmUNCiByZWNlbnRseSB3aXRoIGRlc2t0b3AgcHVibGlzaGluZyBzb2Z0d2FyZSBsaWtlIEFsZHVzIFBhZ2VNYWtlciANCmluY2x1ZGluZyB2ZXJzaW9ucyBvZiBMb3JlbSBJcHN1bTxicj48L3A+', ' 08/10/2021', ' 05:50 PM', '', '', '../uploads/2453353256112923697c80.jpg', 'Male', 139874211, 'Controller', '', '', '', '', '', 'Not Active');

-- --------------------------------------------------------

--
-- Table structure for table `grade`
--

CREATE TABLE `grade` (
  `id` int(11) NOT NULL,
  `name` varchar(40) NOT NULL,
  `basic` int(11) NOT NULL,
  `over_time` int(11) NOT NULL,
  `food` int(11) NOT NULL,
  `p_allowance` int(11) NOT NULL,
  `p_bonus` int(11) NOT NULL,
  `o_bonus` int(45) NOT NULL,
  `t_income` int(11) NOT NULL,
  `t_exp` int(11) NOT NULL,
  `t_amt` int(11) NOT NULL,
  `absent_de` int(11) NOT NULL,
  `nssf` int(11) NOT NULL,
  `pay_as` int(11) NOT NULL,
  `other_d` int(11) NOT NULL,
  `date` varchar(30) NOT NULL,
  `time` varchar(30) NOT NULL,
  `payable` int(11) NOT NULL,
  `net` int(11) NOT NULL,
  `username` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `grade`
--

INSERT INTO `grade` (`id`, `name`, `basic`, `over_time`, `food`, `p_allowance`, `p_bonus`, `o_bonus`, `t_income`, `t_exp`, `t_amt`, `absent_de`, `nssf`, `pay_as`, `other_d`, `date`, `time`, `payable`, `net`, `username`) VALUES
(3, 'Grade 3', 117500, 58570, 12000, 12000, 0, 200500, 20025, 0, 0, 5000, 0, 0, 7, ' 04:53 PM', ' 04:53 PM', 188070, 168045, 'gossage gommess'),
(25, 'Grade G', 420000, 45400, 42511, 0, 45211, 45211, 14552, 4522, 47664, 45210, 4522, 47664, 10000, '05:21 PM', '05:21 PM', 450000, 420000, 'gossage gommess');

-- --------------------------------------------------------

--
-- Table structure for table `group_location`
--

CREATE TABLE `group_location` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `group_location`
--

INSERT INTO `group_location` (`id`, `name`) VALUES
(3, 'Tiper'),
(4, 'Mogas');

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `detail` text NOT NULL,
  `stock` int(11) NOT NULL,
  `section` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`id`, `name`, `detail`, `stock`, `section`) VALUES
(2, 'Radio Call 340 dp', 'Made In German', 14, 'Location'),
(3, 'Tourches', 'made in shinganga vijinini', 20, 'Location'),
(4, 'Touser', 'black and white', 92, 'Employee'),
(5, '', '', 0, ''),
(6, '', '', 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `leave_pass`
--

CREATE TABLE `leave_pass` (
  `id` int(11) NOT NULL,
  `id_no` varchar(30) NOT NULL,
  `date_start` varchar(30) NOT NULL,
  `date_end` varchar(30) NOT NULL,
  `status` varchar(30) NOT NULL,
  `action` varchar(30) NOT NULL,
  `date_insert` varchar(30) NOT NULL,
  `username` varchar(30) NOT NULL,
  `fullname` varchar(30) NOT NULL,
  `day` varchar(8) NOT NULL,
  `month` varchar(10) NOT NULL,
  `authorizer` varchar(30) NOT NULL,
  `authorizer_fullname` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `leave_pass`
--

INSERT INTO `leave_pass` (`id`, `id_no`, `date_start`, `date_end`, `status`, `action`, `date_insert`, `username`, `fullname`, `day`, `month`, `authorizer`, `authorizer_fullname`) VALUES
(5, '25', '06/08/2021', '06/08/2021', 'Pass', 'not finished', '08/06/2021 at  11:16 AM', '', '', '6', '08/2021', '', ''),
(6, '457', '06/08/2021', '06/08/2021', 'Leave', 'not finished', '08/06/2021 at  11:17 AM', '', '', '6', '08/2021', '', ''),
(7, '475', '06/08/2021', '06/08/2021', 'Leave', 'not finished', '08/06/2021 at  11:17 AM', '', '', '6', '08/2021', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `list`
--

CREATE TABLE `list` (
  `id` int(11) NOT NULL,
  `location` int(40) NOT NULL,
  `present` int(40) NOT NULL,
  `absent` int(40) NOT NULL,
  `total_number` int(30) NOT NULL,
  `date` varchar(30) NOT NULL,
  `month` varchar(30) NOT NULL,
  `uploader` varchar(30) NOT NULL,
  `uploader_name` varchar(30) NOT NULL,
  `noon_total` int(11) NOT NULL,
  `night_total` int(11) NOT NULL,
  `noon_present` int(11) NOT NULL,
  `night_present` int(11) NOT NULL,
  `date_word` varchar(20) NOT NULL,
  `leave_people` int(11) NOT NULL,
  `pass` int(11) NOT NULL,
  `noon_absent` int(11) NOT NULL,
  `night_absent` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `list`
--

INSERT INTO `list` (`id`, `location`, `present`, `absent`, `total_number`, `date`, `month`, `uploader`, `uploader_name`, `noon_total`, `night_total`, `noon_present`, `night_present`, `date_word`, `leave_people`, `pass`, `noon_absent`, `night_absent`) VALUES
(1, 0, 6, 2, 0, '08/02/2021', '', '', '', 0, 6, 0, 0, ' August 2, 2021', 0, 0, 0, 1),
(2, 0, 0, 0, 0, '08/03/2021', '', '', '', 0, 0, 0, 0, ' August 3, 2021', 0, 0, 0, 0),
(3, 0, 2, 0, 0, '08/04/2021', '', '', '', 2, 0, 0, 0, ' August 4, 2021', 0, 0, 0, 0),
(6, 0, 0, 0, 0, '05/08/2021', '', '', '', 0, 0, 0, 0, 'August 5, 2021', 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `loan`
--

CREATE TABLE `loan` (
  `id` int(11) NOT NULL,
  `authorizer` varchar(40) NOT NULL,
  `narration` text NOT NULL,
  `ammount` int(11) NOT NULL,
  `id_no` varchar(30) NOT NULL,
  `date_valid` varchar(30) NOT NULL,
  `date_word` varchar(30) NOT NULL,
  `date_number` varchar(30) NOT NULL,
  `time` varchar(30) NOT NULL,
  `uploader` varchar(30) NOT NULL,
  `fullname` varchar(30) NOT NULL,
  `v_no` int(11) NOT NULL,
  `r_date` varchar(30) NOT NULL,
  `status` varchar(20) NOT NULL,
  `r_ammount` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `loan`
--

INSERT INTO `loan` (`id`, `authorizer`, `narration`, `ammount`, `id_no`, `date_valid`, `date_word`, `date_number`, `time`, `uploader`, `fullname`, `v_no`, `r_date`, `status`, `r_ammount`) VALUES
(18, 'abdulaziz gossage', 'kulipa kodi ', 450000, '475', '07/08/2021', ' August 7, 2021', '08/07/2021', '06:18 PM', '', 'juma  kaka ', 0, '29/08/2021', 'Half Paid', 350000),
(19, 'abdulaziz gossage', 'kulipa kodi ', 450000, '475', '07/08/2021', ' August 8, 2021', '08/08/2021', '12:30 PM', '', 'juma  kaka ', 1, '29/08/2021', 'Half Paid', 400000),
(20, 'abdulaziz gossage', 'kulipa kodi ', 450000, '475', '07/08/2021', ' August 8, 2021', '08/08/2021', '12:51 PM', '', 'juma  kaka ', 2, '29/08/2021', 'Not Payed', 450000);

-- --------------------------------------------------------

--
-- Table structure for table `loan_return`
--

CREATE TABLE `loan_return` (
  `id` int(11) NOT NULL,
  `fullname` varchar(50) NOT NULL,
  `id_no` int(11) NOT NULL,
  `ammount` int(11) NOT NULL,
  `loan_amount` int(11) NOT NULL,
  `ammount_remain` int(11) NOT NULL,
  `date` varchar(30) NOT NULL,
  `time` varchar(30) NOT NULL,
  `id_no_loan` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `loan_return`
--

INSERT INTO `loan_return` (`id`, `fullname`, `id_no`, `ammount`, `loan_amount`, `ammount_remain`, `date`, `time`, `id_no_loan`) VALUES
(11, 'juma  kaka ', 475, 50000, 450000, 400000, '08/08/2021', '12:33 PM', 19),
(14, '', 0, 0, 0, 0, '16/08/2021', '02:26 PM', 0);

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

CREATE TABLE `location` (
  `id` int(11) NOT NULL,
  `name` varchar(40) NOT NULL,
  `code` varchar(40) NOT NULL,
  `symbol` varchar(40) NOT NULL,
  `requirment` int(11) NOT NULL,
  `minimum` int(11) NOT NULL,
  `day` int(11) NOT NULL,
  `night` int(11) NOT NULL,
  `radio` int(11) NOT NULL,
  `armed` int(11) NOT NULL,
  `emergecy_no` varchar(100) NOT NULL,
  `location_supeviour` varchar(20) NOT NULL,
  `bios` varchar(11) NOT NULL,
  `date_joined` varchar(15) NOT NULL,
  `date` varchar(11) NOT NULL,
  `time` varchar(11) NOT NULL,
  `username` varchar(40) NOT NULL,
  `Fullname` varchar(100) NOT NULL,
  `under` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `location`
--

INSERT INTO `location` (`id`, `name`, `code`, `symbol`, `requirment`, `minimum`, `day`, `night`, `radio`, `armed`, `emergecy_no`, `location_supeviour`, `bios`, `date_joined`, `date`, `time`, `username`, `Fullname`, `under`) VALUES
(3, 'Tiper', 'Tango one', 't1', 5, 4, 2, 1, 7, 2, '0715511420', 'Play', 'PHA+PHNwYW4', '05/07/2021', '07/2021', ' 04:58 PM', 'gomess_jay', 'Gossage Gommess', 'tiper'),
(4, 'Mogas', 'mike', 'mp', 23, 23, 2, 2, 2, 2, '0715511420', '33', 'PHA+dGhzaSZ', '06/07/2021', '07/2021', ' 11:44 AM', 'gomess_jay', 'Gossage Gommess', 'MOGAS'),
(5, 'Hass', 'hotel', 'h2', 23, 23, 2, 2, 2, 2, '0715511420', '33', 'PHA+dGhzaSZ', '06/07/2021', '07/2021', ' 11:55 AM', 'gomess_jay', 'Gossage Gommess', 'MOGAS'),
(7, 'Mogas Godwoon', 'mike one', 'mk', 23, 1, 23, 2, 2, 2, '2', '457', 'bG9jYXR0ZWQ', '06/07/2021', '07/2021', ' 11:18 AM', 'gomess_jay', 'Gossage Gommess', 'MOGAS'),
(9, 'Tiper pipline', 'tango p', 'tp', 25, 25, 5, 54, 54, 54, '54', 'Supvisor', 'PHA+YWRzZmF', '09/08/2021', '08/2021', ' 06:07 PM', 'gomess_jay', 'Gossage Gommess', 'Tiper'),
(10, 'hotel one', 'hotel', 'h2', 23, 23, 2, 2, 2, 2, '0715511420', '33', 'PHA+dGhzaSZ', '06/07/2021', '08/2021', ' 12:26 PM', 'gomess_jay', 'Gossage Gommess', 'MOGAS');

-- --------------------------------------------------------

--
-- Table structure for table `location_check`
--

CREATE TABLE `location_check` (
  `id` int(11) NOT NULL,
  `name` varchar(40) NOT NULL,
  `1` text NOT NULL,
  `2` text NOT NULL,
  `3` text NOT NULL,
  `4` text NOT NULL,
  `5` text NOT NULL,
  `6` text NOT NULL,
  `7` text NOT NULL,
  `8` text NOT NULL,
  `9` text NOT NULL,
  `10` text NOT NULL,
  `11` text NOT NULL,
  `12` text NOT NULL,
  `13` text NOT NULL,
  `14` text NOT NULL,
  `15` text NOT NULL,
  `16` text NOT NULL,
  `17` text NOT NULL,
  `18` text NOT NULL,
  `19` text NOT NULL,
  `20` text NOT NULL,
  `21` text NOT NULL,
  `22` text NOT NULL,
  `23` text NOT NULL,
  `24` text NOT NULL,
  `uploader` varchar(30) NOT NULL,
  `date_exp` varchar(30) NOT NULL,
  `situation_time` varchar(30) NOT NULL,
  `date_insert` varchar(30) NOT NULL,
  `s_date` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `location_check`
--

INSERT INTO `location_check` (`id`, `name`, `1`, `2`, `3`, `4`, `5`, `6`, `7`, `8`, `9`, `10`, `11`, `12`, `13`, `14`, `15`, `16`, `17`, `18`, `19`, `20`, `21`, `22`, `23`, `24`, `uploader`, `date_exp`, `situation_time`, `date_insert`, `s_date`) VALUES
(79, 'Mogas', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '13/07/2021', '01:30 PM', '13/7/2021', '01:30:00'),
(80, 'Hass', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '13/07/2021', '01:30 PM', '13/7/2021', '01:30:00'),
(81, 'Tiper', '', '', '', '', '', '', '', '', '', '', '', '', '', 'PHNwYW4gc3R5bGU9ImJhY2tncm91bm', '', '', '', '', '', '', '', '', '', '', '', '12/08/2021', '02:10 PM', '12/8/2021', '02:10:00'),
(82, 'Mogas', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '12/08/2021', '02:10 PM', '12/8/2021', '02:10:00'),
(83, 'Hass', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '12/08/2021', '02:10 PM', '12/8/2021', '02:10:00'),
(84, 'Mogas Godwoon', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '12/08/2021', '02:10 PM', '12/8/2021', '02:10:00'),
(85, 'Tiper pipline', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '12/08/2021', '02:10 PM', '12/8/2021', '02:10:00'),
(86, 'hotel one', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '12/08/2021', '02:10 PM', '12/8/2021', '02:10:00'),
(87, 'hotel one', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '12/08/2021', '02:10 PM', '12/8/2021', '02:10:00'),
(88, 'hotel one', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '12/08/2021', '02:10 PM', '12/8/2021', '02:10:00'),
(89, 'hotel one', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '12/08/2021', '02:10 PM', '12/8/2021', '02:10:00'),
(90, 'Tiper', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '12/08/2021', '02:19 PM', '12/8/2021', '02:19:00'),
(91, 'Mogas', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '12/08/2021', '02:19 PM', '12/8/2021', '02:19:00'),
(92, 'Hass', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '12/08/2021', '02:19 PM', '12/8/2021', '02:19:00'),
(93, 'Mogas Godwoon', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '12/08/2021', '02:19 PM', '12/8/2021', '02:19:00'),
(94, 'Tiper pipline', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '12/08/2021', '02:19 PM', '12/8/2021', '02:19:00'),
(95, 'hotel one', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '12/08/2021', '02:19 PM', '12/8/2021', '02:19:00'),
(96, 'hotel one', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '12/08/2021', '02:19 PM', '12/8/2021', '02:19:00'),
(97, 'hotel one', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '12/08/2021', '02:19 PM', '12/8/2021', '02:19:00'),
(98, 'hotel one', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '12/08/2021', '02:19 PM', '12/8/2021', '02:19:00'),
(99, 'Tiper', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '12/08/2021', '02:30 PM', '12/8/2021', '02:30:00'),
(100, 'Mogas', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '12/08/2021', '02:30 PM', '12/8/2021', '02:30:00'),
(101, 'Hass', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '12/08/2021', '02:30 PM', '12/8/2021', '02:30:00'),
(102, 'Mogas Godwoon', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '12/08/2021', '02:30 PM', '12/8/2021', '02:30:00'),
(103, 'Tiper pipline', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '12/08/2021', '02:30 PM', '12/8/2021', '02:30:00'),
(104, 'hotel one', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '12/08/2021', '02:30 PM', '12/8/2021', '02:30:00'),
(105, 'hotel one', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '12/08/2021', '02:30 PM', '12/8/2021', '02:30:00'),
(106, 'hotel one', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '12/08/2021', '02:30 PM', '12/8/2021', '02:30:00'),
(107, 'hotel one', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '12/08/2021', '02:30 PM', '12/8/2021', '02:30:00'),
(108, 'Tiper', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '12/08/2021', '03:00 PM', '12/8/2021', '03:00:00'),
(109, 'Mogas', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '12/08/2021', '03:00 PM', '12/8/2021', '03:00:00'),
(110, 'Hass', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '12/08/2021', '03:00 PM', '12/8/2021', '03:00:00'),
(111, 'Mogas Godwoon', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '12/08/2021', '03:00 PM', '12/8/2021', '03:00:00'),
(112, 'Tiper pipline', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '12/08/2021', '03:00 PM', '12/8/2021', '03:00:00'),
(113, 'hotel one', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '12/08/2021', '03:00 PM', '12/8/2021', '03:00:00'),
(114, 'hotel one', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '12/08/2021', '03:00 PM', '12/8/2021', '03:00:00'),
(115, 'hotel one', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '12/08/2021', '03:00 PM', '12/8/2021', '03:00:00'),
(116, 'hotel one', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '12/08/2021', '03:00 PM', '12/8/2021', '03:00:00'),
(117, 'Tiper', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'PHNwYW4gc3R5bGU9Ig0KCQkJCQkJCQ0KCQkJCQkJCQ0KCQkJCQkJCXBhZGRpbmc6MnB4Ow0KCQkJCQkJDQoJCQkJCQkNCgkJCQkJCQkiDQoJCQkJCQkJDQoJCQkJCQkJPjxpbWcgc3JjPSJpbWcvdGljay5wbmciLz4gMTI6MjAgUE0gJm5ic3AgUmVwbGllZDwvc3Bhbj4NCg==', '', '', '', '', '', '', '', '', '', '', '12/08/2021', '03:30 PM', '12/8/2021', '03:30:00'),
(118, 'Mogas', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'PHNwYW4gc3R5bGU9Ig0KCQkJCQkJCQ0KCQkJCQkJCQ0KCQkJCQkJCXBhZGRpbmc6MnB4Ow0KCQkJCQkJDQoJCQkJCQkNCgkJCQkJCQkiDQoJCQkJCQkJDQoJCQkJCQkJPjxpbWcgc3JjPSJpbWcvdGljay5wbmciLz4gMTI6MjAgUE0gJm5ic3AgUmVwbGllZDwvc3Bhbj4NCg==', '', '', '', '', '', '', '', '', '', '', '12/08/2021', '03:30 PM', '12/8/2021', '03:30:00'),
(119, 'Hass', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'PHNwYW4gc3R5bGU9Ig0KCQkJCQkJDQoJCQkJCQkJDQoJCQkJCQkJcGFkZGluZzoycHg7DQoJCQkJCQkJDQoJCQkJCQkNCgkJCQkJCQkiDQoJCQkJCQkJDQoJCQkJCQkJPjxpbWcgc3JjPSJpbWcvY2xvc2UucG5nIi8+IDEyOjIwIFBNICZuYnNwIG5vdCByZXBsaWVkPC9zcGFuPg0K ', '', '', '', '', '', '', '', '', '', '', '12/08/2021', '03:30 PM', '12/8/2021', '03:30:00'),
(120, 'Mogas Godwoon', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '12/08/2021', '03:30 PM', '12/8/2021', '03:30:00'),
(121, 'Tiper pipline', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ' PHNwYW4gc3R5bGU9Ig0KCQkJCQkJDQoJCQkJCQkJDQoJCQkJCQkJcGFkZGluZzoycHg7DQoJCQkJCQkJDQoJCQkJCQkNCgkJCQkJCQkiDQoJCQkJCQkJDQoJCQkJCQkJPjxpbWcgc3JjPSJpbWcvY2xvc2UucG5nIi8+IDEyOjIwIFBNICZuYnNwIG5vdCByZXBsaWVkPC9zcGFuPg0K  PHNwYW4gc3R5bGU9Ig0KCQkJCQkJDQoJCQkJCQkJDQoJCQkJCQkJcGFkZGluZzoycHg7DQoJCQkJCQkJDQoJCQkJCQkNCgkJCQkJCQkiDQoJCQkJCQkJDQoJCQkJCQkJPjxpbWcgc3JjPSJpbWcvY2xvc2UucG5nIi8+ICAwMzozMCBQTSA8L3NwYW4+DQo= ', '', '', '', '', '', '', '', '', '', '', '12/08/2021', '03:30 PM', '12/8/2021', '03:30:00'),
(122, 'hotel one', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '12/08/2021', '03:30 PM', '12/8/2021', '03:30:00'),
(123, 'Tiper', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '12/08/2021', '04:00 PM', '12/8/2021', '04:00:00'),
(124, 'Mogas', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '12/08/2021', '04:00 PM', '12/8/2021', '04:00:00'),
(125, 'Hass', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '12/08/2021', '04:00 PM', '12/8/2021', '04:00:00'),
(126, 'Mogas Godwoon', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '12/08/2021', '04:00 PM', '12/8/2021', '04:00:00'),
(127, 'Tiper pipline', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '12/08/2021', '04:00 PM', '12/8/2021', '04:00:00'),
(128, 'hotel one', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '12/08/2021', '04:00 PM', '12/8/2021', '04:00:00'),
(129, 'Tiper', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '12/08/2021', '04:30 PM', '12/8/2021', '04:30:00'),
(130, 'Mogas', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '12/08/2021', '04:30 PM', '12/8/2021', '04:30:00'),
(131, 'Hass', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '12/08/2021', '04:30 PM', '12/8/2021', '04:30:00'),
(132, 'Mogas Godwoon', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '12/08/2021', '04:30 PM', '12/8/2021', '04:30:00'),
(133, 'Tiper pipline', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '12/08/2021', '04:30 PM', '12/8/2021', '04:30:00'),
(134, 'hotel one', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '12/08/2021', '04:30 PM', '12/8/2021', '04:30:00'),
(135, 'Tiper', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '12/08/2021', '05:00 PM', '12/8/2021', '05:00:00'),
(136, 'Mogas', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '12/08/2021', '05:00 PM', '12/8/2021', '05:00:00'),
(137, 'Hass', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '12/08/2021', '05:00 PM', '12/8/2021', '05:00:00'),
(138, 'Mogas Godwoon', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '12/08/2021', '05:00 PM', '12/8/2021', '05:00:00'),
(139, 'Tiper pipline', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '12/08/2021', '05:00 PM', '12/8/2021', '05:00:00'),
(140, 'hotel one', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '12/08/2021', '05:00 PM', '12/8/2021', '05:00:00'),
(141, 'Tiper', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '18/08/2021', '02:30 PM', '18/8/2021', '02:30:00'),
(142, 'Mogas', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '18/08/2021', '02:30 PM', '18/8/2021', '02:30:00'),
(143, 'Hass', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '18/08/2021', '02:30 PM', '18/8/2021', '02:30:00'),
(144, 'Mogas Godwoon', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '18/08/2021', '02:30 PM', '18/8/2021', '02:30:00'),
(145, 'Tiper pipline', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '18/08/2021', '02:30 PM', '18/8/2021', '02:30:00'),
(146, 'hotel one', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '18/08/2021', '02:30 PM', '18/8/2021', '02:30:00'),
(147, 'Tiper', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '18/08/2021', '03:00 PM', '18/8/2021', '03:00:00'),
(148, 'Mogas', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '18/08/2021', '03:00 PM', '18/8/2021', '03:00:00'),
(149, 'Hass', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '18/08/2021', '03:00 PM', '18/8/2021', '03:00:00'),
(150, 'Mogas Godwoon', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '18/08/2021', '03:00 PM', '18/8/2021', '03:00:00'),
(151, 'Tiper pipline', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '18/08/2021', '03:00 PM', '18/8/2021', '03:00:00'),
(152, 'hotel one', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '18/08/2021', '03:00 PM', '18/8/2021', '03:00:00'),
(153, 'Tiper', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '18/08/2021', '03:30 PM', '18/8/2021', '03:30:00'),
(154, 'Mogas', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '18/08/2021', '03:30 PM', '18/8/2021', '03:30:00'),
(155, 'Hass', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '18/08/2021', '03:30 PM', '18/8/2021', '03:30:00'),
(156, 'Mogas Godwoon', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '18/08/2021', '03:30 PM', '18/8/2021', '03:30:00'),
(157, 'Tiper pipline', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '18/08/2021', '03:30 PM', '18/8/2021', '03:30:00'),
(158, 'hotel one', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '18/08/2021', '03:30 PM', '18/8/2021', '03:30:00'),
(159, 'Tiper', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '18/08/2021', '04:00 PM', '18/8/2021', '04:00:00'),
(160, 'Mogas', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '18/08/2021', '04:00 PM', '18/8/2021', '04:00:00'),
(161, 'Hass', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '18/08/2021', '04:00 PM', '18/8/2021', '04:00:00'),
(162, 'Mogas Godwoon', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '18/08/2021', '04:00 PM', '18/8/2021', '04:00:00'),
(163, 'Tiper pipline', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '18/08/2021', '04:00 PM', '18/8/2021', '04:00:00'),
(164, 'hotel one', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '18/08/2021', '04:00 PM', '18/8/2021', '04:00:00'),
(165, 'Tiper', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '18/08/2021', '04:30 PM', '18/8/2021', '04:30:00'),
(166, 'Mogas', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '18/08/2021', '04:30 PM', '18/8/2021', '04:30:00'),
(167, 'Hass', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '18/08/2021', '04:30 PM', '18/8/2021', '04:30:00'),
(168, 'Mogas Godwoon', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '18/08/2021', '04:30 PM', '18/8/2021', '04:30:00'),
(169, 'Tiper pipline', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '18/08/2021', '04:30 PM', '18/8/2021', '04:30:00'),
(170, 'hotel one', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '18/08/2021', '04:30 PM', '18/8/2021', '04:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `location_d`
--

CREATE TABLE `location_d` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(30) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `alt_no` varchar(30) NOT NULL,
  `postion` varchar(30) NOT NULL,
  `location` varchar(30) NOT NULL,
  `notfication` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `location_d`
--

INSERT INTO `location_d` (`id`, `name`, `email`, `phone`, `alt_no`, `postion`, `location`, `notfication`) VALUES
(2, 'Othman Mkude', '255743511420', '25571661152', 'abdulazizgossage909@gmail.com', 'plant manager', 'Tiper', 'yes'),
(3, 'mzee kisunzi', '255715511420', '25571661152', 'abdulazizgossage@yahoo.com', 'security officer', 'Mogas', 'yes'),
(4, 'HAJI mAOUD', '255715511420', '255715511420', 'abdulazizgossage909@gmail.com', 'manger', 'Mogasyes', ''),
(8, 'haji kiwelu', '2557511420', '255715511420', 'abdulazizgossage909@gmail.com', 'hssq', 'Tiper', 'yes'),
(9, 'haji kiwelu', '2557511420', '255715511420', 'abdulazizgossage909@gmail.com', 'hssq', 'Tiper', 'no');

-- --------------------------------------------------------

--
-- Table structure for table `location_numbers`
--

CREATE TABLE `location_numbers` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `email` varchar(30) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `alt_phone` varchar(30) NOT NULL,
  `postion` varchar(30) NOT NULL,
  `location` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `location_numbers`
--

INSERT INTO `location_numbers` (`id`, `name`, `email`, `phone`, `alt_phone`, `postion`, `location`) VALUES
(0, 'Abdulaziz Gossage', '0715511420', '', 'abdulazizgossage909@gmail.com', 'd', 'Tiper');

-- --------------------------------------------------------

--
-- Table structure for table `managment`
--

CREATE TABLE `managment` (
  `id` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  `grade` varchar(30) NOT NULL,
  `department` varchar(30) NOT NULL,
  `email` varchar(30) NOT NULL,
  `name` varchar(40) NOT NULL,
  `fullname` varchar(50) NOT NULL,
  `phone_no` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `managment`
--

INSERT INTO `managment` (`id`, `number`, `grade`, `department`, `email`, `name`, `fullname`, `phone_no`) VALUES
(3, 457, '', 'asdf', 'yes', '', 'kalisa hzi', '255715511420'),
(4, 530, '', 'asdf', 'yes', '', 'kalisa hzi', '255715511420'),
(5, 475, '', 'asdf', 'no', '', 'kalisa hzi', '255715511420'),
(6, 475, '', 'asdf', 'yes', '', 'kalisa hzi', '255715511420'),
(7, 475, '', 'asdf', 'yes', '', 'juma  1991587121', '0715511420'),
(8, 0, '', '', 'Location', '', ' ', ''),
(9, 0, '', '', '', '', ' ', '');

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `id` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `type` varchar(30) NOT NULL,
  `status` varchar(30) NOT NULL,
  `action` varchar(30) NOT NULL,
  `date_master` varchar(30) NOT NULL,
  `sender` varchar(30) NOT NULL,
  `id_no` varchar(30) NOT NULL,
  `time` varchar(30) NOT NULL,
  `fullname` varchar(50) NOT NULL,
  `date_insert` varchar(30) NOT NULL,
  `month` varchar(30) NOT NULL,
  `day` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `notification`
--

INSERT INTO `notification` (`id`, `username`, `type`, `status`, `action`, `date_master`, `sender`, `id_no`, `time`, `fullname`, `date_insert`, `month`, `day`) VALUES
(11, 'Gommes', '', '', 'not_deleted', '04/08/2021', '', '214', '', 'Abdulaziz', '04/08/2021', '08/2021', '4'),
(12, 'Gommes', '', ' ', 'not_deleted', '04/08/2021', '', '214', '', 'Abdulaziz', '04/08/2021', '08/2021', '4');

-- --------------------------------------------------------

--
-- Table structure for table `not_`
--

CREATE TABLE `not_` (
  `id` int(11) NOT NULL,
  `ob no` varchar(22) NOT NULL,
  `username` varchar(40) NOT NULL,
  `id_situation` int(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `not_sr`
--

CREATE TABLE `not_sr` (
  `id` int(11) NOT NULL,
  `ob_no` varchar(30) NOT NULL,
  `id_s` varchar(30) NOT NULL,
  `username` varchar(30) NOT NULL,
  `status` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `not_sr`
--

INSERT INTO `not_sr` (`id`, `ob_no`, `id_s`, `username`, `status`) VALUES
(1, '2147483647', '32', '', 'not'),
(2, '2147483647', '33', 'abdlaziz gosageg', 'not');

-- --------------------------------------------------------

--
-- Table structure for table `pay_rol`
--

CREATE TABLE `pay_rol` (
  `id` int(11) NOT NULL,
  `name` varchar(80) NOT NULL,
  `location` varchar(80) NOT NULL,
  `id_no` int(11) NOT NULL,
  `available` int(11) NOT NULL,
  `not_available` int(11) NOT NULL,
  `total_days` int(11) NOT NULL,
  `ammount` int(11) NOT NULL,
  `deduct_ammount` int(11) NOT NULL,
  `month` varchar(15) NOT NULL,
  `date` varchar(15) NOT NULL,
  `bank` varchar(30) NOT NULL,
  `gross_ammount` int(11) NOT NULL,
  `grade` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pay_rol`
--

INSERT INTO `pay_rol` (`id`, `name`, `location`, `id_no`, `available`, `not_available`, `total_days`, `ammount`, `deduct_ammount`, `month`, `date`, `bank`, `gross_ammount`, `grade`) VALUES
(1, 'juma  lastname', 'Tiper', 475, 2, 0, 2, 168045, 0, '08/2021', '02/08/2021', '014547855', 0, 'Grade 3'),
(2, 'kalisa lastname', 'Tiper', 214, 2, 0, 2, 168045, 0, '08/2021', '02/08/2021', '014545522', 0, 'Grade 3'),
(3, 'abdlaziz lastname', 'Tiper', 25, 0, 1, 0, 163045, 0, '08/2021', '02/08/2021', '5', 168045, 'Grade 3'),
(4, 'juma lastname', 'Tiper', 534, 1, 0, 1, 168045, 0, '08/2021', '02/08/2021', '014545522', 0, 'Grade 3'),
(5, 'paulo lastname', 'Tiper', 533, 1, 0, 1, 168045, 0, '08/2021', '02/08/2021', '014545522', 0, 'Grade 3'),
(6, 'chumong lastname', 'Mogas', 457, 1, 0, 1, 168045, 0, '08/2021', '02/08/2021', '011141112', 168045, 'Grade 3'),
(7, 'hamza lastname', 'Mogas', 577, 0, 1, 0, 163045, 5000, '08/2021', '02/08/2021', '014545522', 168045, 'Grade 3'),
(8, 'jackson  lastname', 'Mogas', 530, 1, 0, 1, 168045, 0, '08/2021', '02/08/2021', '014545522', 168045, 'Grade 3');

-- --------------------------------------------------------

--
-- Table structure for table `petty`
--

CREATE TABLE `petty` (
  `id` int(11) NOT NULL,
  `authorizer` varchar(40) NOT NULL,
  `narration` text NOT NULL,
  `ammount` int(11) NOT NULL,
  `id_no` varchar(30) NOT NULL,
  `date_valid` varchar(30) NOT NULL,
  `date_word` varchar(30) NOT NULL,
  `date_number` varchar(30) NOT NULL,
  `time` varchar(30) NOT NULL,
  `uploader` varchar(30) NOT NULL,
  `fullname` varchar(30) NOT NULL,
  `v_no` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `petty`
--

INSERT INTO `petty` (`id`, `authorizer`, `narration`, `ammount`, `id_no`, `date_valid`, `date_word`, `date_number`, `time`, `uploader`, `fullname`, `v_no`) VALUES
(4, 'asdf', 'asdfa', 45000, '25', '07/31/2021', ' July 31, 2021', '07/31/2021', '6', '', '', 0),
(5, 'asdf', 'asdfa', 45000, '25', '07/31/2021', ' July 31, 2021', '07/31/2021', '6', '', '', 0),
(6, 'asdf', 'asdfa', 45000, '25', '07/31/2021', ' July 31, 2021', '07/31/2021', '06:23 PM', '', '', 1),
(7, 'asdf', 'asdfa', 45000, '25', '07/31/2021', ' July 31, 2021', '07/31/2021', '06:24 PM', '', '', 2),
(8, 'asdf', 'asdfa', 45000, '25', '10/08/2021', ' August 1, 2021', '08/01/2021', '03:22 PM', '', '', 3),
(9, 'dsfasf', 'adsfdsf', 5000000, '457', '01/08/2021', ' August 1, 2021', '08/01/2021', '04:50 PM', '', '', 4),
(10, 'dsfasf', 'adsfdsf', 5000000, '457', '01/08/2021', ' August 1, 2021', '08/01/2021', '04:54 PM', '', '', 5),
(11, 'sdasf', 'adfsaf', 333, '25', '01/08/2021', ' August 1, 2021', '08/01/2021', '04:58 PM', '', '', 6);

-- --------------------------------------------------------

--
-- Table structure for table `purchase`
--

CREATE TABLE `purchase` (
  `id` int(11) NOT NULL,
  `invoice` varchar(30) NOT NULL,
  `name` varchar(30) NOT NULL,
  `qty` int(11) NOT NULL,
  `cost` int(11) NOT NULL,
  `t_cost` int(11) NOT NULL,
  `detail` text NOT NULL,
  `date` varchar(30) NOT NULL,
  `date_insert` int(11) NOT NULL,
  `time_insert` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `purchase`
--

INSERT INTO `purchase` (`id`, `invoice`, `name`, `qty`, `cost`, `t_cost`, `detail`, `date`, `date_insert`, `time_insert`) VALUES
(1, '3', 'Radio Call 340 dp', 3, 3, 3, 'dasfadfs', '09/07/2021', 7, 7),
(2, 'then new', 'Touser', 3, 899999, 5000000, 'ne ', '05/07/2021', 7, 7);

-- --------------------------------------------------------

--
-- Table structure for table `situtation_report`
--

CREATE TABLE `situtation_report` (
  `id` int(11) NOT NULL,
  `detail` text NOT NULL,
  `ob_no_send` int(45) NOT NULL,
  `ob_no_recived` int(85) NOT NULL,
  `title` varchar(100) NOT NULL,
  `location` varchar(40) NOT NULL,
  `date` varchar(20) NOT NULL,
  `time` varchar(30) NOT NULL,
  `username` varchar(30) NOT NULL,
  `uploader_name` varchar(30) NOT NULL,
  `status2` varchar(30) NOT NULL,
  `location_name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `situtation_report`
--

INSERT INTO `situtation_report` (`id`, `detail`, `ob_no_send`, `ob_no_recived`, `title`, `location`, `date`, `time`, `username`, `uploader_name`, `status2`, `location_name`) VALUES
(1, 'PHA+ZHNkPGJyPjwvcD4=', 0, 0, '', '0', '0', '', '', '', '', ''),
(2, 'PHA+czxicj48L3A+', 1, 989, 'chasdf', 'tiper', '07/28/2021', '07:33 PM', '', '', '', ''),
(3, 'PHA+czxicj48L3A+', 2, 989, 'chasdf', 'tiper', '07/28/2021', '07:39 PM', '', '', '', ''),
(4, 'PHA+ZHNkPGJyPjwvcD4=', 3, 989, 'chasdf', 'tiper', '07/28/2021', '07:39 PM', '', '', 'js', ''),
(5, 'PHA+YWQ8YnI+PC9wPg==', 4, 989, 'chasdf', 'tiper', '07/28/2021', '07:40 PM', '', '', 'Normal', ''),
(6, 'PHA+YXM8YnI+PC9wPg==', 5, 989, 'chasdf', 'tiper', '07/28/2021', '07:41 PM', '', '', 'Normal', 'tiper'),
(7, 'PHA+dGhlbjxicj48L3A+', 6, 984, 'tesing', 'tiper', '07/29/2021', '06:10 PM', '', '', 'High', 'tiper'),
(8, 'PHA+c3NkZmE8YnI+PC9wPg==', 7, 984, 'tesing', 'tiper', '07/29/2021', '06:16 PM', '', '', 'Normal', 'tiper'),
(9, 'PHA+YXNkZjxicj48L3A+', 8, 984, 'tesing', 'tiper', '07/29/2021', '06:22 PM', '', '', 'Normal', 'tiper'),
(10, 'PHA+YXNkZmFzZGY8YnI+PC9wPg==', 9, 984, 'tesing', 'tiper', '07/29/2021', '06:22 PM', '', '', 'High', 'tiper'),
(11, 'PHA+CjwvcD48ZGl2Pgo8aDI+V2h5IGRvIHdlIHVzZSBpdD88L2gyPgo8cD5JdCBpcyBhIGxvbmcgZXN0YWJsaXNoZWQgZmFjdCB0aGF0IGEgcmVhZGVyIHdpbGwgYmUgZGlzdHJhY3RlZCBieSB0aGUKIHJlYWRhYmxlIGNvbnRlbnQgb2YgYSBwYWdlIHdoZW4gbG9va2luZyBhdCBpdHMgbGF5b3V0LiBUaGUgcG9pbnQgb2YgCnVzaW5nIExvcmVtIElwc3VtIGlzIHRoYXQgaXQgaGFzIGEgbW9yZS1vci1sZXNzIG5vcm1hbCBkaXN0cmlidXRpb24gb2YgCmxldHRlcnMsIGFzIG9wcG9zZWQgdG8gdXNpbmcgJ0NvbnRlbnQgaGVyZSwgY29udGVudCBoZXJlJywgbWFraW5nIGl0IApsb29rIGxpa2UgcmVhZGFibGUgRW5nbGlzaC4gTWFueSBkZXNrdG9wIHB1Ymxpc2hpbmcgcGFja2FnZXMgYW5kIHdlYiAKcGFnZSBlZGl0b3JzIG5vdyB1c2UgTG9yZW0gSXBzdW0gYXMgdGhlaXIgZGVmYXVsdCBtb2RlbCB0ZXh0LCBhbmQgYSAKc2VhcmNoIGZvciAnbG9yZW0gaXBzdW0nIHdpbGwgdW5jb3ZlciBtYW55IHdlYiBzaXRlcyBzdGlsbCBpbiB0aGVpciAKaW5mYW5jeS4gVmFyaW91cyB2ZXJzaW9ucyBoYXZlIGV2b2x2ZWQgb3ZlciB0aGUgeWVhcnMsIHNvbWV0aW1lcyBieSAKYWNjaWRlbnQsIHNvbWV0aW1lcyBvbiBwdXJwb3NlIChpbmplY3RlZCBodW1vdXIgYW5kIHRoZSBsaWtlKS48L3A+CjwvZGl2Pg==', 10, 74111, 'testing', 'tiper', '07/30/2021', '11:32 AM', '', '', 'Normal', 'tiper'),
(12, 'PHA+YXNkZnNhZjxicj48L3A+', 11, 145441, 'testing', 'tiper', '08/13/2021', '12:27 PM', '', '', 'Normal', 'tiper'),
(13, 'PHA+YXNkZnNhZjxicj48L3A+', 12, 145441, 'testing', 'tiper', '08/13/2021', '12:27 PM', '', '', 'Normal', 'tiper'),
(14, 'PHA+YXNkZnNhZjxicj48L3A+', 13, 145441, 'testing', 'tiper', '08/13/2021', '12:28 PM', '', '', 'Normal', 'tiper'),
(15, 'PHA+YXNkZmFzZmRhc2Y8L3A+PHA+YXNkZnNhZjwvcD48cD5hc2RmPGJyPjwvcD4=', 14, 78242, 'yes', 'tiper', '08/19/2021', '02:31 PM', '', '', 'Normal', 'tiper'),
(16, 'PHA+d2VlZTxicj48L3A+', 15, 47541, 'kufunguliwa kwa geti', 'Tiper', '08/19/2021', '02:37 PM', '', '', 'Normal', 'Tiper'),
(17, 'PHA+YXNmZDxicj48L3A+', 16, 47541, 'kufunguliwa kwa geti', 'Tiper', '08/19/2021', '03:16 PM', '', '', 'Normal', 'Tiper'),
(18, 'PHA+c2FkZjxicj48L3A+', 17, 47544, 'kufunguliwa kwa geti', 'Tiper', '08/19/2021', '03:19 PM', '', '', 'Normal', 'Tiper'),
(25, 'PHA+c2FkZjxicj48L3A+', 24, 2147483647, 'kufunguliwa kwa geti', 'Tiper', '08/19/2021', '03:41 PM', 'abdulaziz', 'abdlaziz gosageg', 'Normal', 'Tiper'),
(30, 'PHA+d2VycTxicj48L3A+', 25, 2147483647, 'kufunguliwa kwa geti', 'Tiper', '08/19/2021', '03:49 PM', 'abdulaziz', 'abdlaziz gosageg', 'Normal', 'Tiper'),
(31, 'PGRpdj4KPGgyPldoYXQgaXMgTG9yZW0gSXBzdW0/PC9oMj4KPHA+PHN0cm9uZz5Mb3JlbSBJcHN1bTwvc3Ryb25nPiBpcyBzaW1wbHkgZHVtbXkgdGV4dCBvZiB0aGUgcHJpbnRpbmcgYW5kCiB0eXBlc2V0dGluZyBpbmR1c3RyeS4gTG9yZW0gSXBzdW0gaGFzIGJlZW4gdGhlIGluZHVzdHJ5J3Mgc3RhbmRhcmQgCmR1bW15IHRleHQgZXZlciBzaW5jZSB0aGUgMTUwMHMsIHdoZW4gYW4gdW5rbm93biBwcmludGVyIHRvb2sgYSBnYWxsZXkgCm9mIHR5cGUgYW5kIHNjcmFtYmxlZCBpdCB0byBtYWtlIGEgdHlwZSBzcGVjaW1lbiBib29rLiBJdCBoYXMgc3Vydml2ZWQgCm5vdCBvbmx5IGZpdmUgY2VudHVyaWVzLCBidXQgYWxzbyB0aGUgbGVhcCBpbnRvIGVsZWN0cm9uaWMgdHlwZXNldHRpbmcsIApyZW1haW5pbmcgZXNzZW50aWFsbHkgdW5jaGFuZ2VkLiBJdCB3YXMgcG9wdWxhcmlzZWQgaW4gdGhlIDE5NjBzIHdpdGggCnRoZSByZWxlYXNlIG9mIExldHJhc2V0IHNoZWV0cyBjb250YWluaW5nIExvcmVtIElwc3VtIHBhc3NhZ2VzLCBhbmQgbW9yZQogcmVjZW50bHkgd2l0aCBkZXNrdG9wIHB1Ymxpc2hpbmcgc29mdHdhcmUgbGlrZSBBbGR1cyBQYWdlTWFrZXIgCmluY2x1ZGluZyB2ZXJzaW9ucyBvZiBMb3JlbSBJcHN1bS48L3A+CjwvZGl2Pg==', 26, 2147483647, 'kufunguliwa kwa geti', 'Tiper', '08/19/2021', '03:59 PM', 'abdulaziz', 'abdlaziz gosageg', 'Normal', 'Tiper'),
(32, 'PGRpdj5ld2hlbiBhbiB1bmtub3duIHByaW50ZXIgdG9vayBhIGdhbGxleSAKb2YgdHlwZSBhbmQgc2NyYW1ibGVkIGl0IHRvIG1ha2UgYSA8YnI+PC9kaXY+', 27, 2147483647, 'kufunguliwa kwa geti', 'tiper', '08/20/2021', '11:58 AM', 'abdulaziz', 'abdlaziz gosageg', 'Normal', 'tiper'),
(33, 'PGRpdj4KPGgyPldoYXQgaSBMb3JlbSBJcHN1bT88L2gyPgo8cD48c3Ryb25nPkxvcmVtIElwc3VtPC9zdHJvbmc+IGlzIHNpbXBseSBkdW1teSB0ZXh0IG9mIHRoZSBwcmludGluZyBhbmQKIHR5cGVzZXR0aW5nIGluZHVzdHJ5LiBMb3JlbSBJcHN1bSBoYXMgYmVlbiB0aGUgaW5kdXN0cnkncyBzdGFuZGFydXQgYWxzbyB0aGUgbGVhcCBpbnRvIGVsZWN0cm9uaWMgdHlwZXNldHRpbmcsIApyZW1haW5pbmcgZXNzZW50aWFsbHkgdW5jaGFuZ2VkLiBJdCB3YXMgcG9wdWxhcmlzZWQgaW4gdGhlIDE5NjBzIHdpdGggCnRoZSByZWxlYXNlIG9mIExldHJhc2V0IHNoZWV0cyBjb250YWluaW5nIExvcmVtIElwc3VtIHBhc3NhZ2VzLCBhbmQgbW9yZQogcmVjZW50bHkgd2l0aCBkZXNrdG9wIHB1Ymxpc2hpbmcgc29mdHdhcmUgbGlrZSBBbGR1cyBQYWdlTWFrZXIgCmluY2x1ZGluZyB2ZXJzaW9ucyBvZiBMb3JlbSBJcHN1bS48L3A+CjwvZGl2Pg==', 28, 2147483647, 'kufunguliwa kwa geti', 'tiper', '08/20/2021', '12:03 PM', 'abdulaziz', 'abdlaziz gosageg', 'Normal', 'tiper');

-- --------------------------------------------------------

--
-- Table structure for table `update_sr`
--

CREATE TABLE `update_sr` (
  `id` int(11) NOT NULL,
  `detail` text NOT NULL,
  `ob_no_send` varchar(40) NOT NULL,
  `ob_no_recived` varchar(40) NOT NULL,
  `title` text NOT NULL,
  `location` varchar(40) NOT NULL,
  `date` varchar(10) NOT NULL,
  `time` varchar(10) NOT NULL,
  `username` varchar(30) NOT NULL,
  `uploader_name` varchar(40) NOT NULL,
  `status2` varchar(40) NOT NULL,
  `location_name` varchar(40) NOT NULL,
  `id_send` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `update_sr`
--

INSERT INTO `update_sr` (`id`, `detail`, `ob_no_send`, `ob_no_recived`, `title`, `location`, `date`, `time`, `username`, `uploader_name`, `status2`, `location_name`, `id_send`) VALUES
(1, 'PGRpdj5ld2hlbiBhbiB1bmtub3duIHByaW50ZXIgdG9vayBhIGdhbGxleSAKb2YgdHlwZSBhbmQgc2NyYW1ibGVkIGl0IHRvIG1ha2UgYSA8YnI+PC9kaXY+', '', '2147483647', 'kufunguliwa kwa geti', 'tiper', '08/20/2021', '12:25 PM', 'abdulaziz', 'abdlaziz gosageg', 'Normal', 'tiper', 0),
(2, 'PGRpdj5ld2hlbiBhbiB1bmtub3duIHByaW50ZXIgdG9vayBhIGdhbGxleSAKb2YgdHlwZSBhbmQgc2NyYW1ibGVkIGl0IHRvIG1ha2UgYSA8YnI+PC9kaXY+', '', '2147483647', 'kufunguliwa kwa geti', 'tiper', '08/20/2021', '02:50 PM', 'Feisal', '', 'Normal', 'tiper', 32),
(3, 'PGRpdj4KPGgyPldoYXQgaSBMb3JlbSBJcHN1bT88L2gyPgo8cD48c3Ryb25nPkxvcmVtIElwc3VtPC9zdHJvbmc+IGlzIHNpbXBseSBkdW1teSB0ZXh0IG9mIHRoZSBwcmludGluZyBhbmQKIHR5cGVzZXR0aW5nIGluZHVzdHJ5LiBMb3JlbSBJcHN1bSBoYXMgYmVlbiB0aGUgaW5kdXN0cnkncyBzdGFuZGFydXQgYWxzbyB0aGUgbGVhcCBpbnRvIGVsZWN0cm9uaWMgdHlwZXNldHRpbmcsIApyZW1haW5pbmcgZXNzZW50aWFsbHkgdW5jaGFuZ2VkLiBJdCB3YXMgcG9wdWxhcmlzZWQgaW4gdGhlIDE5NjBzIHdpdGggCnRoZSByZWxlYXNlIG9mIExldHJhc2V0IHNoZWV0cyBjb250YWluaW5nIExvcmVtIElwc3VtIHBhc3NhZ2VzLCBhbmQgbW9yZQogcmVjZW50bHkgd2l0aCBkZXNrdG9wIHB1Ymxpc2hpbmcgc29mdHdhcmUgbGlrZSBBbGR1cyBQYWdlTWFrZXIgCmluY2x1ZGluZyB2ZXJzaW9ucyBvZiBMb3JlbSBJcHN1bS48L3A+CjwvZGl2Pg==', '', '2147483647', 'kufunguliwa kwa geti', 'tiper', '08/20/2021', '02:52 PM', 'abdulaziz', 'abdlaziz gosageg', 'Normal', 'tiper', 33);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `role` varchar(40) NOT NULL,
  `username` varchar(40) NOT NULL,
  `password` varchar(40) NOT NULL,
  `id_no` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `role`, `username`, `password`, `id_no`) VALUES
(8, 'Admin', 'abdulaziz', '123', 25),
(9, 'Controller', 'ziro', 'abd123', 475);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `dsf` int(11) NOT NULL,
  `image` text NOT NULL,
  `othe` varchar(34) NOT NULL,
  `asdf` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `dsf`, `image`, `othe`, `asdf`) VALUES
(1, '', 0, '../uploads/8504243360e975634b9f7.jpg', '', 0),
(2, 'uwezo', 0, '../uploads/53235038860eed5b604b61.jpg', '', 0),
(3, 'uwezo', 0, '../uploads/192159693460eed5d105f6e.jpg', '', 0),
(4, 'uwezo', 0, '../uploads/210877975460eed5f543b73.jpg', '', 0),
(5, 'uwezo', 0, '../uploads/151566340360eed60d1ecff.jpg', '', 0),
(6, 'uwezo', 0, '../uploads/25488947660eed63b070cf.jpg', '', 0),
(7, 'uwezo', 0, '../uploads/56789773660eed64cdc52a.jpg', '', 0),
(8, 'uwezo', 0, '../uploads/94128155760eed65aa80f8.jpg', '', 0),
(9, 'uwezo', 0, '../uploads/61917546860eed6c6e9bc6.jpg', '', 0),
(10, 'uwezo', 0, '../uploads/170381244560eed6ef0d67b.jpg', '', 0),
(11, 'uwezo', 0, '../uploads/188801942660eed7023715a.jpg', '', 0),
(12, 'uwezo', 0, '../uploads/14395941460eed7107b87e.jpg', '', 0),
(13, 'uwezo', 0, '../uploads/33038177660eed77bbadb8.jpg', '', 0),
(14, 'uwezo', 0, '../uploads/50102275460eed7bf8a7ff.jpg', '', 0),
(15, 'uwezo', 0, '../uploads/62215422660eed7f6860cd.jpg', '', 0),
(16, 'uwezo', 0, '../uploads/87903803560eed851657b4.jpg', '', 0),
(17, 'uwezo', 0, '../uploads/88870013860eed87bbab62.jpg', '', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deployment`
--
ALTER TABLE `deployment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deployment_view`
--
ALTER TABLE `deployment_view`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `grade`
--
ALTER TABLE `grade`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `group_location`
--
ALTER TABLE `group_location`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leave_pass`
--
ALTER TABLE `leave_pass`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `list`
--
ALTER TABLE `list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loan`
--
ALTER TABLE `loan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loan_return`
--
ALTER TABLE `loan_return`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `location`
--
ALTER TABLE `location`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `location_check`
--
ALTER TABLE `location_check`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `location_d`
--
ALTER TABLE `location_d`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `managment`
--
ALTER TABLE `managment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `not_`
--
ALTER TABLE `not_`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `not_sr`
--
ALTER TABLE `not_sr`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pay_rol`
--
ALTER TABLE `pay_rol`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `petty`
--
ALTER TABLE `petty`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase`
--
ALTER TABLE `purchase`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `situtation_report`
--
ALTER TABLE `situtation_report`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `update_sr`
--
ALTER TABLE `update_sr`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `deployment`
--
ALTER TABLE `deployment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `deployment_view`
--
ALTER TABLE `deployment_view`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `grade`
--
ALTER TABLE `grade`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `group_location`
--
ALTER TABLE `group_location`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `leave_pass`
--
ALTER TABLE `leave_pass`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `list`
--
ALTER TABLE `list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `loan`
--
ALTER TABLE `loan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `loan_return`
--
ALTER TABLE `loan_return`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `location`
--
ALTER TABLE `location`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `location_check`
--
ALTER TABLE `location_check`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=171;

--
-- AUTO_INCREMENT for table `location_d`
--
ALTER TABLE `location_d`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `managment`
--
ALTER TABLE `managment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `not_`
--
ALTER TABLE `not_`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `not_sr`
--
ALTER TABLE `not_sr`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pay_rol`
--
ALTER TABLE `pay_rol`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `petty`
--
ALTER TABLE `petty`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `purchase`
--
ALTER TABLE `purchase`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `situtation_report`
--
ALTER TABLE `situtation_report`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `update_sr`
--
ALTER TABLE `update_sr`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
