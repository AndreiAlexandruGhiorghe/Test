-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 14, 2020 at 04:35 PM
-- Server version: 10.4.13-MariaDB
-- PHP Version: 7.4.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `test_table`
--

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` int(11) NOT NULL,
  `creation_date` date NOT NULL,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `address` varchar(255) COLLATE utf8_bin NOT NULL,
  `comments` varchar(255) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id`, `creation_date`, `name`, `address`, `comments`) VALUES
(11, '2020-07-06', 'andrei', 'andrei@yahoo.com', '                       asdasd                                 '),
(12, '2020-07-06', 'andrei', 'andrei@yahoo.com', '       asdads                                                 '),
(13, '2020-07-06', 'andrei', 'andrei@yahoo.com', '       asdads                                                 '),
(14, '2020-07-14', 'andrei', 'andrei@yahoo.com', '        asdasdasd                    '),
(15, '2020-07-14', 'andrei', 'andrei@yahoo.com', '        asdasdasd                    '),
(16, '2020-07-14', 'andrei', 'andrei@yahoo.com', '        asdasdasd                    '),
(17, '2020-07-14', 'andrei', 'andrei@yahoo.com', '        asdasdasd                    '),
(18, '2020-07-14', 'andrei', 'andrei@yahoo.com', '        asdasdasd                    '),
(19, '2020-07-14', 'andrei', 'andrei@yahoo.com', '        asdasdasd                    '),
(20, '2020-07-14', 'andrei', 'andrei@yahoo.com', '        asdasdasd                    '),
(21, '2020-07-14', 'andrei', 'andrei@yahoo.com', '        asdasdasd                    '),
(22, '2020-07-14', 'andrei', 'andrei@yahoo.com', '        asdasdasd                    '),
(23, '2020-07-14', 'andrei', 'andrei@yahoo.com', '        asdasdasd                    '),
(24, '2020-07-14', 'andrei', 'andrei@yahoo.com', '        asdasdasd                    '),
(25, '2020-07-14', 'andrei', 'andrei@yahoo.com', '        asdasdasd                    '),
(26, '2020-07-14', 'andrei', 'andrei@yahoo.com', '            asdasd                '),
(27, '2020-07-14', 'andrei', 'andrei@yahoo.com', '        asdasd                    '),
(28, '2020-07-14', 'andrei', 'andrei@yahoo.com', '         asdasdasd                   '),
(29, '2020-07-14', 'TestName', 'andrei@yahoo.com', '      asdasdasd                      '),
(30, '2020-07-14', 'andrei', 'andrei@yahoo.com', '         asdasd                   ');

-- --------------------------------------------------------

--
-- Table structure for table `order_products`
--

CREATE TABLE `order_products` (
  `id` int(11) NOT NULL,
  `id_order` int(11) NOT NULL,
  `id_product` int(11) NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `order_products`
--

INSERT INTO `order_products` (`id`, `id_order`, `id_product`, `quantity`) VALUES
(1, 11, 3, 1),
(2, 11, 8, 1),
(3, 12, 8, 1),
(4, 13, 8, 1),
(5, 14, 3, 1),
(6, 14, 4, 1),
(7, 15, 3, 1),
(8, 15, 4, 1),
(9, 16, 3, 1),
(10, 16, 4, 1),
(11, 17, 3, 1),
(12, 17, 4, 1),
(13, 18, 3, 1),
(14, 18, 4, 1),
(15, 19, 3, 1),
(16, 19, 4, 1),
(17, 20, 3, 1),
(18, 20, 4, 1),
(19, 21, 3, 1),
(20, 21, 4, 1),
(21, 22, 3, 1),
(22, 22, 4, 1),
(23, 23, 3, 1),
(24, 23, 4, 1),
(25, 24, 3, 1),
(26, 24, 4, 1),
(27, 25, 3, 1),
(28, 25, 4, 1),
(29, 26, 18, 1),
(30, 27, 18, 1),
(31, 27, 17, 1),
(32, 27, 30, 1),
(33, 28, 3, 1),
(34, 29, 17, 2),
(35, 29, 7, 1),
(36, 30, 3, 4),
(37, 30, 4, 4),
(38, 30, 5, 3),
(39, 30, 7, 2);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_bin NOT NULL,
  `description` varchar(255) COLLATE utf8_bin NOT NULL,
  `price` int(11) NOT NULL,
  `image_path` varchar(255) COLLATE utf8_bin NOT NULL,
  `inventory` int(11) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `title`, `description`, `price`, `image_path`, `inventory`) VALUES
(3, 'Telefon APPLE iPhone Xs', '512GB, Silver', 6279, 'images/1593779448iPhoneXs-Silver_3_8dc0314f.jpg', 60),
(4, 'Telefon SAMSUNG Galaxy Z Flip', '256GB, 8GB RAM, Dual SIM, Mirror Purple', 5949, 'images/1593689571Galaxy-Z-Flip_purple_4_7818a103.jpg', 80),
(5, 'Telefon SAMSUNG Galaxy S10 Plus', '128GB, 8GB RAM, Dual SIM, Teal Green', 3167, 'images/1593689720SMTG975FZGD_6_03f0d4b6.jpg', 97),
(7, 'Test', 'A test', 14, 'images/1593773742unnamed.jpg', 97),
(8, 'Well', 'Tell', 6666, 'images/1593779252iPhoneXs-Silver_3_8dc0314f.jpg', 100),
(17, 'good', 'good taste', 1111, 'images/1594207365thumb-good.jpg', 95),
(18, 'not good at all', 'maybe good, just maybe', 1212, 'images/1594210115thumb-good.jpg', 0),
(23, 'Test', 'A test', 12, 'images/1594299452unnamed.jpg', 100),
(25, 'a', 'asdasda', 123123123, 'images/1594381513unnamed.jpg', 100),
(29, 'asdasd', 'asdasd', 1, 'images/1594646492unnamed.jpg', 4),
(31, 'asdasd', 'A test', 123, 'images/1594732005unnamed.jpg', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_products`
--
ALTER TABLE `order_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `order_products`
--
ALTER TABLE `order_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
