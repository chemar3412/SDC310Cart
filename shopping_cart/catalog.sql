-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 04, 2026 at 06:45 AM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `products`
--

-- --------------------------------------------------------

--
-- Table structure for table `catalog`
--

CREATE TABLE `catalog` (
  `product_id` varchar(15) NOT NULL,
  `product_name` varchar(50) NOT NULL,
  `description` varchar(250) NOT NULL,
  `cost` int(10) NOT NULL,
  `quantity` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `catalog`
--

INSERT INTO `catalog` (`product_id`, `product_name`, `description`, `cost`, `quantity`) VALUES
('1', 'Wireless Headphones', 'High-quality wireless headphone noise cancellation 30HOUR battery life', 80, 0),
('2', 'USB-C Cable', 'Durable 6ft USB-C charging AND DATA transfer cable', 15, 0),
('3', 'Portable POWER Bank', '20000 mAh portable POWER bank WITH DUAL USB PORT AND FAST charging capability', 35, 0),
('4', 'Phone Screen Protector', 'Tempered glass screen protector WITH 9H hardness AND anti-fingerprint coating.', 10, 0),
('5', 'Bluetooth Speaker', 'COMPACT waterproof bluetooth speaker WITH 12-HOUR battery AND 360-degree sound', 50, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `catalog`
--
ALTER TABLE `catalog`
  ADD PRIMARY KEY (`product_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
