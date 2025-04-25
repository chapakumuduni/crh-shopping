-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 25, 2025 at 05:11 AM
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
  `parent_id` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `parent_id`, `description`, `image`, `created_at`) VALUES
(1, 'Women', NULL, NULL, NULL, '2025-04-24 11:46:45'),
(2, 'Men', NULL, NULL, NULL, '2025-04-24 11:46:45'),
(3, 'Bag', NULL, NULL, NULL, '2025-04-24 11:46:45'),
(4, 'Shoes', NULL, NULL, NULL, '2025-04-24 11:46:45'),
(5, 'Watches', NULL, NULL, NULL, '2025-04-24 11:46:45'),
(6, 'Dresses', NULL, 'Elegant and casual dresses for all occasions', NULL, '2025-03-12 08:31:37'),
(7, 'Jackets', NULL, 'Winter and fashion jackets for men and women', NULL, '2025-03-12 08:31:37'),
(8, 'Shoes', NULL, 'Casual, formal, and sports shoes', NULL, '2025-03-12 08:31:37'),
(9, 'Accessories', NULL, 'Hats, belts, sunglasses, and more', NULL, '2025-03-12 08:31:37'),
(10, 'Kids Wear', NULL, 'Clothing for babies, toddlers, and kids', NULL, '2025-03-12 08:31:37'),
(11, 'Activewear', NULL, 'Sportswear and gym outfits', NULL, '2025-03-12 08:31:37'),
(12, 'Formal Wear', NULL, 'Suits, blazers, and business casual clothing', NULL, '2025-03-12 08:31:37'),
(13, 'Ethnic Wear', NULL, 'Traditional clothing like sarees, kurtas, and more', NULL, '2025-03-12 08:31:37'),
(14, 'rt', NULL, 'qqqq', '1745232633_Screenshot 2025-01-28 221809.png', '2025-04-21 02:50:33');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `customer_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `status` enum('Active','Inactive','Banned') DEFAULT 'Active',
  `created_at` datetime DEFAULT current_timestamp(),
  `address` text DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`customer_id`, `name`, `email`, `status`, `created_at`, `address`, `phone`) VALUES
(21, 'Customer 21', 'customer21@example.com', 'Banned', '2025-04-21 20:13:01', 'Address 21', '123-456-7890'),
(22, 'Customer 22', 'customer22@example.com', 'Active', '2025-04-21 20:14:12', 'Address 22', '234-567-8901'),
(23, 'Customer 23', 'customer23@example.com', 'Active', '2025-04-21 20:15:23', 'Address 23', '345-678-9012'),
(24, 'Customer 24', 'customer24@example.com', 'Active', '2025-04-21 20:16:34', 'Address 24', '456-789-0123'),
(25, 'Customer 25', 'customer25@example.com', 'Active', '2025-04-21 20:17:45', 'Address 25', '567-890-1234');

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
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('Pending','Shipped','Delivered','Cancelled') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `payment_method` enum('Credit Card','PayPal','Bank Transfer','Cash on Delivery','Stripe') NOT NULL DEFAULT 'Credit Card'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_price`, `order_date`, `status`, `created_at`, `payment_method`) VALUES
(53, 21, 100.50, '2025-04-21 06:43:01', 'Cancelled', '2025-04-21 06:43:01', 'PayPal'),
(54, 22, 150.75, '2025-04-21 06:44:12', 'Shipped', '2025-04-21 06:44:12', 'Credit Card'),
(55, 23, 200.00, '2025-04-21 06:45:23', 'Delivered', '2025-04-21 06:45:23', 'Credit Card'),
(56, 24, 50.00, '2025-04-21 06:46:34', 'Cancelled', '2025-04-21 06:46:34', 'Credit Card'),
(57, 25, 300.40, '2025-04-21 06:47:45', 'Pending', '2025-04-21 06:47:45', 'Credit Card');

-- --------------------------------------------------------

--
-- Table structure for table `order_products`
--

CREATE TABLE `order_products` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` enum('Pending','Completed','Failed','Refunded') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
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
  `category_id` int(11) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'Active',
  `stock_quantity` int(10) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `image`, `stock`, `created_at`, `category_id`, `status`, `stock_quantity`) VALUES
(1, 'Esprit Ruffle Shirt', 'Nulla eget sem vitae eros pharetra viverra. Nam vitae luctus ligula. Mauris consequat ornare feugiat.', 16.64, 'images/product-01.jpg', 20, '2025-03-10 05:48:24', 1, 'Active', 100),
(2, 'Herschel supply', 'Nulla eget sem vitae eros pharetra viverra. Nam vitae luctus ligula. Mauris consequat ornare feugiat.', 35.31, 'images/product-02.jpg', 10, '2025-03-10 05:48:24', 1, 'Active', 25),
(3, 'Check Trouser', 'Nulla eget sem vitae eros pharetra viverra. Nam vitae luctus ligula. Mauris consequat ornare feugiat.', 25.50, 'images/product-03.jpg', 12, '2025-03-10 05:48:24', 2, 'Active', 0),
(4, 'Classic Trench Coat', 'Nulla eget sem vitae eros pharetra viverra. Nam vitae luctus ligula. Mauris consequat ornare feugiat.', 75.00, 'images/product-04.jpg', 5, '2025-03-10 05:48:24', 1, 'Active', 0),
(5, 'Front Pocket Jumper', 'Nulla eget sem vitae eros pharetra viverra. Nam vitae luctus ligula. Mauris consequat ornare feugiat.', 34.75, 'images/product-05.jpg', 9, '2025-03-10 05:48:24', NULL, 'Active', 0),
(6, 'Vintage Inspired Classic ', 'Nulla eget sem vitae eros pharetra viverra. Nam vitae luctus ligula. Mauris consequat ornare feugiat.', 93.20, 'images/product-06.jpg', 2, '2025-03-10 05:48:24', NULL, 'Active', 0),
(7, 'Shirt in Stretch Cotton', 'Nulla eget sem vitae eros pharetra viverra. Nam vitae luctus ligula. Mauris consequat ornare feugiat.', 52.66, 'images/product-07.jpg', 4, '2025-03-10 05:48:24', NULL, 'Active', 0),
(8, 'Pieces Metallic Printed', 'Nulla eget sem vitae eros pharetra viverra. Nam vitae luctus ligula. Mauris consequat ornare feugiat.', 18.96, 'images/product-08.jpg', 34, '2025-03-10 05:48:24', NULL, 'Active', 0),
(9, 'Converse All Star Hi Plimsolls', 'Nulla eget sem vitae eros pharetra viverra. Nam vitae luctus ligula. Mauris consequat ornare feugiat.', 75.00, 'images/product-09.jpg', 3, '2025-03-10 05:48:24', 1, 'Active', 15),
(10, 'Femme T-Shirt In Stripe', 'Nulla eget sem vitae eros pharetra viverra. Nam vitae luctus ligula. Mauris consequat ornare feugiat.', 25.85, 'images/product-10.jpg', 35, '2025-03-10 05:48:24', NULL, 'Active', 0),
(11, 'Herschel supply ', 'Nulla eget sem vitae eros pharetra viverra. Nam vitae luctus ligula. Mauris consequat ornare feugiat.', 63.16, 'mages/product-11.jpg', 5, '2025-03-10 05:48:24', 1, 'Active', 0),
(12, 'Herschel supply', 'Nulla eget sem vitae eros pharetra viverra. Nam vitae luctus ligula. Mauris consequat ornare feugiat.', 63.15, 'images/product-12.jpg', 5, '2025-03-10 05:48:24', 1, 'Active', 0),
(13, 'T-Shirt with Sleeve', 'Nulla eget sem vitae eros pharetra viverra. Nam vitae luctus ligula. Mauris consequat ornare feugiat.', 18.49, 'images/product-13.jpg', 41, '2025-03-10 05:48:24', NULL, 'Active', 0),
(14, 'Pretty Little Thing', 'Nulla eget sem vitae eros pharetra viverra. Nam vitae luctus ligula. Mauris consequat ornare feugiat.', 54.79, 'images/product-14.jpg', 2, '2025-03-10 05:48:24', NULL, 'Active', 0),
(15, 'Mini Silver Mesh Watch', 'Nulla eget sem vitae eros pharetra viverra. Nam vitae luctus ligula. Mauris consequat ornare feugiat.', 86.85, 'images/product-15.jpg', 1, '2025-03-10 05:48:24', NULL, 'Active', 0),
(16, 'Square Neck Back', 'Nulla eget sem vitae eros pharetra viverra. Nam vitae luctus ligula. Mauris consequat ornare feugiat.', 29.64, 'images/product-16.jpg', 22, '2025-03-10 05:48:24', NULL, 'Active', 0),
(17, 'Haven Long Sleeve Shirt', 'Nulla eget sem vitae eros pharetra viverra. Nam vitae luctus ligula. Mauris consequat ornare feugiat.', 54.79, 'images/product-17.jpg', 2, '2025-03-10 05:48:24', NULL, 'Active', 0),
(18, 'Casual Dress', 'Nulla eget sem vitae eros pharetra viverra. Nam vitae luctus ligula. Mauris consequat ornare feugiat.', 27.00, 'images/product-18.jpg', 20, '2025-03-10 05:48:24', NULL, 'Active', 0),
(19, 'NFL Dallas Cowboys Box Fit Crew Sweater', 'Nulla eget sem vitae eros pharetra viverra. Nam vitae luctus ligula. Mauris consequat ornare feugiat.', 80.00, 'images/product-20.jpg', 2, '2025-03-10 05:48:24', NULL, 'Active', 0),
(20, 'NBA New York Knicks Loose Fit T-Shir', 'Nulla eget sem vitae eros pharetra viverra. Nam vitae luctus ligula. Mauris consequat ornare feugiat.', 44.90, 'images/product-19.jpg', 7, '2025-03-10 05:48:24', NULL, 'Active', 0),
(21, 'Pit Stop Soccer Jerseyd', 'Nulla eget sem vitae eros pharetra viverra. Nam vitae luctus ligula. Mauris consequat ornare feugiat.', 119.90, 'images/product-21.jpg', 1, '2025-03-10 05:48:24', NULL, 'Active', 0),
(22, 'Organic Muscle', 'Nulla eget sem vitae eros pharetra viverra. Nam vitae luctus ligula. Mauris consequat ornare feugiat.', 36.00, 'images/product-22.jpg', 13, '2025-03-10 05:48:24', NULL, 'Active', 0),
(23, 'Essential Sock', 'Nulla eget sem vitae eros pharetra viverra. Nam vitae luctus ligula. Mauris consequat ornare feugiat.', 19.90, 'images/product-23.jpg', 50, '2025-03-10 05:48:24', NULL, 'Active', 0),
(24, 'Tote Bags Brown', 'Nulla eget sem vitae eros pharetra viverra. Nam vitae luctus ligula. Mauris consequat ornare feugiat.', 48.79, 'images/product-24.jpg', 8, '2025-03-10 05:48:24', NULL, 'Active', 0),
(25, 'Ladies white Leather Handbags', 'Nulla eget sem vitae eros pharetra viverra. Nam vitae luctus ligula. Mauris consequat ornare feugiat.', 65.90, 'images/product-25.jpg', 4, '2025-03-10 05:48:24', NULL, 'Active', 0),
(26, 'Nordace Siena Pro 15 Backpack', 'Nulla eget sem vitae eros pharetra viverra. Nam vitae luctus ligula. Mauris consequat ornare feugiat.', 45.79, 'images/product-26.jpg', 6, '2025-03-10 05:48:24', NULL, 'Active', 0),
(27, 'Men Bags', 'Nulla eget sem vitae eros pharetra viverra. Nam vitae luctus ligula. Mauris consequat ornare feugiat.', 30.00, 'images/product-27.jpg', 4, '2025-03-10 05:48:24', NULL, 'Active', 0),
(28, 'Basketball shoe', 'Nulla eget sem vitae eros pharetra viverra. Nam vitae luctus ligula. Mauris consequat ornare feugiat.', 130.00, 'images/product-28.jpg', 2, '2025-03-10 05:48:24', NULL, 'Active', 0),
(29, 'BOXER shoes', 'Nulla eget sem vitae eros pharetra viverra. Nam vitae luctus ligula. Mauris consequat ornare feugiat.', 60.00, 'images/product-29.jpg', 2, '2025-03-10 05:48:24', NULL, 'Active', 0),
(30, 'Casual Loafer Shoes', 'Nulla eget sem vitae eros pharetra viverra. Nam vitae luctus ligula. Mauris consequat ornare feugiat.', 100.00, 'images/product-30.jpg', 2, '2025-03-10 05:48:24', NULL, 'Active', 0),
(31, 'wearables watches', 'Nulla eget sem vitae eros pharetra viverra. Nam vitae luctus ligula. Mauris consequat ornare feugiat.', 150.00, 'images/product-31.jpg', 4, '2025-03-10 05:48:24', NULL, 'Active', 0),
(32, 'Rolex watches', 'Nulla eget sem vitae eros pharetra viverra. Nam vitae luctus ligula. Mauris consequat ornare feugiat.', 400.00, 'images/product-32.jpg', 2, '2025-03-10 05:48:24', NULL, 'Active', 0);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `site_name` varchar(100) NOT NULL,
  `site_email` varchar(100) NOT NULL,
  `theme` varchar(50) DEFAULT 'light',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `site_name`, `site_email`, `theme`, `created_at`) VALUES
(1, 'Ecommerce Admin Panel', 'support@example.com', 'dark', '2025-03-12 04:47:15');

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
(2, 'aa', 'aa', 'aaa bbb', '123123', 'aaa@bbb.com', 'asd asdas asd as address'),
(3, 'abcdef', 'abcdef', 'ABC def', '123456', 'abcdef@gmail.com', 'Andromeda');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','manager','employee') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `role`, `created_at`) VALUES
(1, NULL, 'admin', 'admin@example.com', '$2y$10$HfLWpStewxYxJcHVL7n8/evo4bYJOHZ1nkoCq8cHmtKRzY5Z8ln62', 'admin', '2025-03-12 04:47:15'),
(2, 'John Doe', 'JohnDoe', 'john@example.com', 'password123', 'admin', '2025-03-12 07:56:33'),
(3, 'Jane Smith', 'JaneSmith', 'jane@example.com', 'password123', 'manager', '2025-03-12 07:56:33'),
(4, 'Alice Brown', 'AliceBrown', 'alice@example.com', 'password123', 'employee', '2025-03-12 07:56:33'),
(5, 'Bob White', 'BobWhite', 'bob@example.com', 'password123', 'employee', '2025-03-12 07:56:33'),
(6, 'Charlie Green', 'CharlieGreen', 'charlie@example.com', 'password123', 'manager', '2025-03-12 07:56:33'),
(8, NULL, 'dineth', 'geethika@gmail.com', '$2y$10$WJrhN6LNRFkKok3mNAcD7ONzuO0wuMs.2jwneM47jDZC.fPva5p9O', 'employee', '2025-04-21 02:47:16'),
(10, 'Rumeth', '', 'Rumeth@gmail.com', '$2y$10$6o1abWmliHoovlLBgbcHz.z8noAw3yFsb6s8zpy5l.XztFKHQTOme', 'admin', '2025-04-22 04:09:30'),
(11, 'chapa', '', 'chapa@gmail.com', '$2y$10$y01/yBxJ7iy8DCLDEGBZ5uuP7ctP/ixAkrxjKL3uUgWIGFqsowXYS', 'admin', '2025-04-22 04:23:24');

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
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_products`
--
ALTER TABLE `order_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);
ALTER TABLE `products` ADD FULLTEXT KEY `product_search` (`name`,`description`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
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
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `delivery_address`
--
ALTER TABLE `delivery_address`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `order_products`
--
ALTER TABLE `order_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

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
