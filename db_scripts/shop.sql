-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 12, 2025 at 11:03 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `parent_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `parent_id`) VALUES
(1, 'Women', NULL),
(2, 'Men', NULL),
(3, 'Bag', NULL),
(4, 'Shoes', NULL),
(5, 'Watches', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `delivery_address`
--

CREATE TABLE `delivery_address` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `receipient_name` varchar(50) NOT NULL,
  `receipient_phone` varchar(50) NOT NULL,
  `receipient_email` varchar(50) NOT NULL,
  `receipient_address` varchar(50) NOT NULL,
  `receipient_district` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `stock` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `category_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `image`, `stock`, `created_at`, `category_id`) VALUES
(1, 'Esprit Ruffle Shirt', 'Nulla eget sem vitae eros pharetra viverra. Nam vitae luctus ligula. Mauris consequat ornare feugiat.', 16.64, 'images/product-01.jpg', 20, '2025-03-10 05:48:24', 1),
(2, 'Herschel supply', 'Nulla eget sem vitae eros pharetra viverra. Nam vitae luctus ligula. Mauris consequat ornare feugiat.', 35.31, 'images/product-02.jpg', 10, '2025-03-10 05:48:24', 1),
(3, 'Check Trouser', 'Nulla eget sem vitae eros pharetra viverra. Nam vitae luctus ligula. Mauris consequat ornare feugiat.', 25.50, 'images/product-03.jpg', 12, '2025-03-10 05:48:24', 2),
(4, 'Classic Trench Coat', 'Nulla eget sem vitae eros pharetra viverra. Nam vitae luctus ligula. Mauris consequat ornare feugiat.', 75.00, 'images/product-04.jpg', 5, '2025-03-10 05:48:24', 1),
(5, 'Front Pocket Jumper', 'Nulla eget sem vitae eros pharetra viverra. Nam vitae luctus ligula. Mauris consequat ornare feugiat.', 34.75, 'images/product-05.jpg', 9, '2025-03-10 05:48:24', NULL),
(6, 'Vintage Inspired Classic ', 'Nulla eget sem vitae eros pharetra viverra. Nam vitae luctus ligula. Mauris consequat ornare feugiat.', 93.20, 'images/product-06.jpg', 2, '2025-03-10 05:48:24', NULL),
(7, 'Shirt in Stretch Cotton', 'Nulla eget sem vitae eros pharetra viverra. Nam vitae luctus ligula. Mauris consequat ornare feugiat.', 52.66, 'images/product-07.jpg', 4, '2025-03-10 05:48:24', NULL),
(8, 'Pieces Metallic Printed', 'Nulla eget sem vitae eros pharetra viverra. Nam vitae luctus ligula. Mauris consequat ornare feugiat.', 18.96, 'images/product-08.jpg', 34, '2025-03-10 05:48:24', NULL),
(9, 'Converse All Star Hi Plimsolls', 'Nulla eget sem vitae eros pharetra viverra. Nam vitae luctus ligula. Mauris consequat ornare feugiat.', 75.00, 'images/product-09.jpg', 3, '2025-03-10 05:48:24', NULL),
(10, 'Femme T-Shirt In Stripe', 'Nulla eget sem vitae eros pharetra viverra. Nam vitae luctus ligula. Mauris consequat ornare feugiat.', 25.85, 'images/product-10.jpg', 35, '2025-03-10 05:48:24', NULL),
(11, 'Herschel supply ', 'Nulla eget sem vitae eros pharetra viverra. Nam vitae luctus ligula. Mauris consequat ornare feugiat.', 63.16, 'mages/product-11.jpg', 5, '2025-03-10 05:48:24', 1),
(12, 'Herschel supply', 'Nulla eget sem vitae eros pharetra viverra. Nam vitae luctus ligula. Mauris consequat ornare feugiat.', 63.15, 'images/product-12.jpg', 5, '2025-03-10 05:48:24', 1),
(13, 'T-Shirt with Sleeve', 'Nulla eget sem vitae eros pharetra viverra. Nam vitae luctus ligula. Mauris consequat ornare feugiat.', 18.49, 'images/product-13.jpg', 41, '2025-03-10 05:48:24', NULL),
(14, 'Pretty Little Thing', 'Nulla eget sem vitae eros pharetra viverra. Nam vitae luctus ligula. Mauris consequat ornare feugiat.', 54.79, 'images/product-14.jpg', 2, '2025-03-10 05:48:24', NULL),
(15, 'Mini Silver Mesh Watch', 'Nulla eget sem vitae eros pharetra viverra. Nam vitae luctus ligula. Mauris consequat ornare feugiat.', 86.85, 'images/product-15.jpg', 1, '2025-03-10 05:48:24', NULL),
(16, 'Square Neck Back', 'Nulla eget sem vitae eros pharetra viverra. Nam vitae luctus ligula. Mauris consequat ornare feugiat.', 29.64, 'images/product-16.jpg', 22, '2025-03-10 05:48:24', NULL),
(17, 'Haven Long Sleeve Shirt', 'Nulla eget sem vitae eros pharetra viverra. Nam vitae luctus ligula. Mauris consequat ornare feugiat.', 54.79, 'images/product-17.jpg', 2, '2025-03-10 05:48:24', NULL),
(18, 'Casual Dress', 'Nulla eget sem vitae eros pharetra viverra. Nam vitae luctus ligula. Mauris consequat ornare feugiat.', 27.00, 'images/product-18.jpg', 20, '2025-03-10 05:48:24', NULL),
(19, 'NFL Dallas Cowboys Box Fit Crew Sweater', 'Nulla eget sem vitae eros pharetra viverra. Nam vitae luctus ligula. Mauris consequat ornare feugiat.', 80.00, 'images/product-20.jpg', 2, '2025-03-10 05:48:24', NULL),
(20, 'NBA New York Knicks Loose Fit T-Shir', 'Nulla eget sem vitae eros pharetra viverra. Nam vitae luctus ligula. Mauris consequat ornare feugiat.', 44.90, 'images/product-19.jpg', 7, '2025-03-10 05:48:24', NULL),
(21, 'Pit Stop Soccer Jerseyd', 'Nulla eget sem vitae eros pharetra viverra. Nam vitae luctus ligula. Mauris consequat ornare feugiat.', 119.90, 'images/product-21.jpg', 1, '2025-03-10 05:48:24', NULL),
(22, 'Organic Muscle', 'Nulla eget sem vitae eros pharetra viverra. Nam vitae luctus ligula. Mauris consequat ornare feugiat.', 36.00, 'images/product-22.jpg', 13, '2025-03-10 05:48:24', NULL),
(23, 'Essential Sock', 'Nulla eget sem vitae eros pharetra viverra. Nam vitae luctus ligula. Mauris consequat ornare feugiat.', 19.90, 'images/product-23.jpg', 50, '2025-03-10 05:48:24', NULL),
(24, 'Tote Bags Brown', 'Nulla eget sem vitae eros pharetra viverra. Nam vitae luctus ligula. Mauris consequat ornare feugiat.', 48.79, 'images/product-24.jpg', 8, '2025-03-10 05:48:24', NULL),
(25, 'Ladies white Leather Handbags', 'Nulla eget sem vitae eros pharetra viverra. Nam vitae luctus ligula. Mauris consequat ornare feugiat.', 65.90, 'images/product-25.jpg', 4, '2025-03-10 05:48:24', NULL),
(26, 'Nordace Siena Pro 15 Backpack', 'Nulla eget sem vitae eros pharetra viverra. Nam vitae luctus ligula. Mauris consequat ornare feugiat.', 45.79, 'images/product-26.jpg', 6, '2025-03-10 05:48:24', NULL),
(27, 'Men Bags', 'Nulla eget sem vitae eros pharetra viverra. Nam vitae luctus ligula. Mauris consequat ornare feugiat.', 30.00, 'images/product-27.jpg', 4, '2025-03-10 05:48:24', NULL),
(28, 'Basketball shoe', 'Nulla eget sem vitae eros pharetra viverra. Nam vitae luctus ligula. Mauris consequat ornare feugiat.', 130.00, 'images/product-28.jpg', 2, '2025-03-10 05:48:24', NULL),
(29, 'BOXER shoes', 'Nulla eget sem vitae eros pharetra viverra. Nam vitae luctus ligula. Mauris consequat ornare feugiat.', 60.00, 'images/product-29.jpg', 2, '2025-03-10 05:48:24', NULL),
(30, 'Casual Loafer Shoes', 'Nulla eget sem vitae eros pharetra viverra. Nam vitae luctus ligula. Mauris consequat ornare feugiat.', 100.00, 'images/product-30.jpg', 2, '2025-03-10 05:48:24', NULL),
(31, 'wearables watches', 'Nulla eget sem vitae eros pharetra viverra. Nam vitae luctus ligula. Mauris consequat ornare feugiat.', 150.00, 'images/product-31.jpg', 4, '2025-03-10 05:48:24', NULL),
(32, 'Rolex watches', 'Nulla eget sem vitae eros pharetra viverra. Nam vitae luctus ligula. Mauris consequat ornare feugiat.', 400.00, 'images/product-32.jpg', 2, '2025-03-10 05:48:24', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `user_name` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `full_name` varchar(50) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `residential_address` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `user_name`, `password`, `full_name`, `phone`, `email`, `residential_address`) VALUES
(1, 'ck', 'ck', 'Chapa Kumu', '89898989', 'chapa@kumudini.com', '120/36 Samagi mawatha'),
(2, 'aa', 'aa', 'aaa bbb', '123123', 'aaa@bbb.com', 'asd asdas asd as address');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `delivery_address`
--
ALTER TABLE `delivery_address`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);
ALTER TABLE `products` ADD FULLTEXT KEY `product_search` (`name`,`description`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `delivery_address`
--
ALTER TABLE `delivery_address`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `delivery_address`
--
ALTER TABLE `delivery_address`
  ADD CONSTRAINT `delivery_address_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
