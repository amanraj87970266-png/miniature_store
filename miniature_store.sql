-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 20, 2025 at 07:26 AM
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
-- Database: `miniature_store`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `email`, `password`) VALUES
(1, 'Admin Aman', 'amanraj.87970266@gmail.com', ' $2y$10$/gg8e6YhtA0ajv/q4Vf0/OnjWo3/DNMY2MZ14m1ITL2jILugpHlqS');

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `author` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `manuscript` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `is_featured` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `title`, `author`, `price`, `category`, `image`, `manuscript`, `description`, `category_id`, `is_featured`) VALUES
(1, 'OCTOBER JUNCTION', 'divya prakash dube', 299.00, 'love', 'book1.jpg', NULL, 'stories that touches your heart.', 1, 1),
(2, 'GODAAN', 'munshi premchand', 199.00, 'animal love', 'book2.jpg', '1758199109_godan.pdf', 'Godaan is a Hindi novel by Munshi Premchand. It was first published in 1936 and is considered one of the greatest novels of modern Indian literature. Themed around the socio-economic deprivation as well as the exploitation of the village poor, the novel was the last complete novel of Premchand.', 2, 0),
(3, 'MUSAFIR', 'divya prakash dube', 349.00, 'fiction', 'book3.jpg', NULL, 'Explore the universe.', 3, 0),
(4, 'PREMAALAAP', 'manmohan jha', 300.00, 'poetry', 'book4.jpg', NULL, 'यह किताब आपको प्रेम के वास्तविक रूप से परिचित करवाएगी, साथ ही आपके दिल में उतरकर प्रेम रूपी ईश्वर को भी जन्म देगी।\r\nवो लोग जिनका विश्वास प्रेम के यथार्थ अस्तित्व से उठ गया है, उनको एक वजह देगी प्रेम करने की और प्रेम में होने की।\r\nनिश्चित ही यह किताब पाठकों को प्रेममय कर देगी।', 4, 1),
(5, 'THE ART OF BEING ALONE', 'RENUKA GAVRANI', 199.00, 'self-help', 'book5.jpg', NULL, 'It\'s a complete myth that being alone means being lonely. Being alone doesn\'t mean you are lonely. Being alone means you are with yourself. It\'s ironic how we waste our entire lives waiting for an imaginary person while ignoring our own souls just to realize that \'the only person missing from your side was you.\' ..', 5, 1);

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Fiction'),
(2, 'Non-Fiction'),
(3, 'poetry'),
(4, 'love'),
(5, 'self-help'),
(6, 'animal love'),
(7, 'Science'),
(8, 'History'),
(9, 'Romance');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `book_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `price` decimal(10,2) NOT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `order_date` datetime DEFAULT current_timestamp(),
  `razorpay_payment_id` varchar(100) DEFAULT NULL,
  `purchase_date` datetime NOT NULL DEFAULT current_timestamp(),
  `payment_id` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `book_id`, `quantity`, `total_price`, `status`, `price`, `total`, `order_date`, `razorpay_payment_id`, `purchase_date`, `payment_id`) VALUES
(1, 1, 1, 1, 0.00, 'completed', 299.00, NULL, '2025-09-12 21:30:35', NULL, '2025-09-12 21:30:35', 'pay_RGkQdIAvqkkLeN'),
(2, 1, 2, 2, 0.00, 'completed', 199.00, NULL, '2025-09-12 21:30:35', NULL, '2025-09-12 21:30:35', 'pay_RGkQdIAvqkkLeN'),
(3, 1, 1, 1, 0.00, 'completed', 299.00, NULL, '2025-09-12 21:39:37', NULL, '2025-09-12 21:39:37', 'pay_RGkcXOfoLBSKw8'),
(4, 1, 4, 5, 0.00, 'completed', 300.00, NULL, '2025-09-12 21:39:37', NULL, '2025-09-12 21:39:37', 'pay_RGkcXOfoLBSKw8'),
(5, 1, 4, 1, 0.00, 'completed', 300.00, NULL, '2025-09-18 17:03:51', NULL, '2025-09-18 17:03:51', 'pay_RJ37ydFgiAIUFN'),
(6, 2, 2, 1, 0.00, 'pending', 199.00, NULL, '2025-09-19 17:38:17', NULL, '2025-09-19 17:38:17', 'pay_RJSErukaaXb8i6'),
(7, 1, 1, 1, 0.00, 'pending', 299.00, NULL, '2025-09-20 10:48:35', NULL, '2025-09-20 10:48:35', 'pay_RJjnncUM3f8KP2');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `book_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_settings`
--

CREATE TABLE `tbl_settings` (
  `id` int(11) NOT NULL,
  `site_name` varchar(255) NOT NULL DEFAULT 'Miniature Store',
  `logo` varchar(255) DEFAULT 'logo.png',
  `background` varchar(255) DEFAULT 'background.png',
  `contact_info` text DEFAULT NULL,
  `footer_text` varchar(255) DEFAULT '© 2025 Miniature Store. All rights reserved.',
  `contact_email` varchar(255) DEFAULT 'adm.miniaturestore@gmail.com'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_settings`
--

INSERT INTO `tbl_settings` (`id`, `site_name`, `logo`, `background`, `contact_info`, `footer_text`, `contact_email`) VALUES
(1, 'Miniature Store', 'logo.png', 'background.png', 'adm.miniaturestore@gmail.com', '© 2025 Miniature Store. All rights reserved.', 'adm.miniaturestore@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `role` enum('user','admin') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `address`, `role`) VALUES
(1, 'Aman Raj', 'aman873raj@gmail.com', '$2y$10$kjIIQbG5/lphMoHdTG6SveZimP9u7fd19hp3nVcnkTMGCEDV2ykBu', 'benipur, darbhanga', 'user'),
(2, ' Aman', 'amanraj.87970266@gmail.com', '$2y$10$/gg8e6YhtA0ajv/q4Vf0/OnjWo3/DNMY2MZ14m1ITL2jILugpHlqS', NULL, 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `tbl_settings`
--
ALTER TABLE `tbl_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_settings`
--
ALTER TABLE `tbl_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `books_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `fk_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
