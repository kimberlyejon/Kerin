-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 03, 2025 at 09:55 AM
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
-- Database: `estrada_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `rating` decimal(2,1) NOT NULL,
  `date_added` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `image_url`, `rating`, `date_added`) VALUES
(1, 'Lipstick', 1200.00, 'https://sdcdn.io/mac/ph/mac_sku_S4K001_1x1_0.png?width=1440&height=1440', 4.8, '2024-07-01'),
(2, 'Hand Cream', 950.00, 'https://www.loccitane.com/dw/image/v2/BDQL_PRD/on/demandware.static/-/Sites-occ_master/default/dwbc93d98a/CA/24MA075R23.png?sw=500&sh=500', 4.5, '2024-06-15'),
(3, 'Face Serum', 1500.00, 'https://www.kiehls.com.ph/wp-content/uploads/2022/04/kiehls-face-serum-powerful-strength-line-reducing-concentrate-50ml-000-3605971536090-front.jpg', 4.9, '2024-05-20'),
(4, 'Body Lotion', 1800.00, 'https://medias.watsons.com.ph/publishing/WTCPH-50037028-front-zoom.jpg?version=1721932737', 4.7, '2024-04-10'),
(5, 'Lip Gloss', 1300.00, 'https://static.beautytocare.com/cdn-cgi/image/width=1600,height=1600,f=auto/media/catalog/product//m/a/makeup-revolution-juicy-pout-lip-gloss-grapefruit-4-6ml_1.png', 4.6, '2024-03-05'),
(6, 'Face Cream', 1400.00, 'https://shop.flawless.com.ph/cdn/shop/files/CLEAR_Anti_Blemish_Face_Cream_Box2.jpg?v=1722994126', 4.4, '2024-02-18'),
(7, 'Eye Shadow', 1600.00, 'https://makeupocean.com/wp-content/uploads/2024/05/makeup-palette-3-1.webp', 4.3, '2024-01-30'),
(8, 'Nail Polish', 850.00, 'https://naturerepublic.com.ph/cdn/shop/files/NF0453.jpg?v=1691998314', 4.2, '2024-01-10'),
(9, 'Blush', 1100.00, 'https://www.elfcosmetics.com/dw/image/v2/BBXC_PRD/on/demandware.static/-/Sites-elf-master/default/dw9e02a47a/2024/PrimerInfusedMatteBlush/83749_OpenA_V6_R.png', 4.7, '2023-12-20'),
(10, 'Foundation', 1300.00, 'https://medias.watsons.com.ph/publishing/WTCPH-50001914-front-zoom.jpg?version=1722486228', 4.8, '2023-12-01'),
(11, 'Face Mask', 1700.00, 'https://www.patchology.com/cdn/shop/files/beauty-sleep-mask-6-pack_1.jpg?v=1726089260', 4.6, '2023-11-10'),
(12, 'Lip Balm', 1050.00, 'https://assets.unileversolutions.com/v1/2322812.png', 4.5, '2023-10-25'),
(13, 'Moisturizer', 1450.00, 'https://www.cetaphil.com/on/demandware.static/-/Library-Sites-RefArchSharedLibrary/default/dwb7a10a7a/083422_GC_MAM_3oz_Tube-Front.PNG', 4.9, '2023-10-01'),
(14, 'Sunscreen', 1900.00, 'https://www.rosepharmacy.com/ph1/wp-content/uploads/2024/06/78373.png', 4.7, '2023-09-15'),
(15, 'Hair Oil', 1350.00, 'https://thebodyshop.com.ph/cdn/shop/products/1061545_2_HAIROILCOCONUT200ML.jpg?v=1748707937', 4.4, '2023-09-01'),
(16, 'Shampoo', 1600.00, 'https://static.beautytocare.com/cdn-cgi/image/width=1600,height=1600,f=auto/media/catalog/product//o/g/ogx-thick-full-biotin-collagen-shampoo-385ml_2.jpg', 4.3, '2023-08-10'),
(17, 'Conditioner', 1300.00, 'https://static.beautytocare.com/cdn-cgi/image/f=auto/media/catalog/product//o/g/ogx-nourishing-coconut-milk-conditioner-88-7ml_1_1.jpg', 4.2, '2023-07-20'),
(18, 'Body Scrub', 1000.00, 'https://medias.watsons.com.ph/publishing/WTCPH-10101552-front-zoom.jpg?version=1721949325', 4.6, '2023-07-01'),
(19, 'Perfume', 1450.00, 'https://png.pngtree.com/png-vector/20240202/ourmid/pngtree-perfume-bottle-mockup-cutout-png-file-png-image_11588760.png', 4.8, '2023-06-15'),
(20, 'Makeup Remover', 2000.00, 'https://images-na.ssl-images-amazon.com/images/I/6117U77YcEL.jpg', 4.7, '2023-06-01');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `address` varchar(255) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `user_id`, `total`, `address`, `payment_method`, `created_at`) VALUES
(1, 1, 950.00, '413 Kimchi, Seoul, QC, 1234, South Korea', 'credit_card', '2025-07-02 09:27:19'),
(2, 1, 8200.00, '413 Kimchi, Seoul, QC, 1234, South Korea', 'credit_card', '2025-07-02 09:38:48'),
(3, 1, 9400.00, '413 Kimchi, Seoul, QC, 1234, South Korea', 'credit_card', '2025-07-02 10:14:12'),
(4, 1, 5450.00, '413 Kimchi, Seoul, QC, 1234, South Korea', 'credit_card', '2025-07-02 10:15:24'),
(5, 1, 2700.00, '413 Kimchi, Seoul, QC, 1234, South Korea', 'credit_card', '2025-07-02 10:16:53'),
(6, 1, 3300.00, '413 Kimchi, Seoul, QC, 1234, South Korea', 'credit_card', '2025-07-03 06:31:05'),
(7, 1, 26100.00, '413 Kimchi, Seoueqweqwe, QC123123, 1234, South Korea', 'credit_card', '2025-07-03 06:40:38'),
(8, 1, 3500.00, '413 Kimchi, Seoueqweqwe, QC123123, 1234, South Korea', 'cod', '2025-07-03 07:43:04');

-- --------------------------------------------------------

--
-- Table structure for table `transaction_items`
--

CREATE TABLE `transaction_items` (
  `id` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaction_items`
--

INSERT INTO `transaction_items` (`id`, `transaction_id`, `product_id`, `quantity`) VALUES
(1, 1, 2, 1),
(2, 2, 3, 1),
(3, 2, 2, 2),
(4, 2, 1, 1),
(5, 2, 4, 2),
(6, 3, 7, 5),
(7, 3, 6, 1),
(8, 4, 3, 1),
(9, 4, 4, 1),
(10, 4, 2, 1),
(11, 4, 1, 1),
(12, 5, 3, 1),
(13, 5, 1, 1),
(14, 6, 3, 1),
(15, 6, 4, 1),
(16, 7, 3, 13),
(17, 7, 1, 4),
(18, 7, 4, 1),
(19, 8, 3, 1),
(20, 8, 20, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `birth_date` date NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `street` varchar(100) NOT NULL,
  `city` varchar(50) NOT NULL,
  `province` varchar(50) NOT NULL,
  `zip_code` varchar(10) NOT NULL,
  `country` varchar(50) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `gender`, `birth_date`, `phone_number`, `email`, `street`, `city`, `province`, `zip_code`, `country`, `username`, `password`) VALUES
(1, 'Kimberly Ejon', 'Female', '2005-04-20', '09164000034', 'kim@gmail.com', '413 Kimchi', 'Seoueqweqwe', 'QC123123', '1234', 'South Korea', 'kimkimchi', '$2y$10$9NyZ8kWLIcug/J6k7VeIG.4LrmRL7Pz5cjaCZKC5HwSY3yMTRNjxq');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_cart` (`user_id`,`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `transaction_items`
--
ALTER TABLE `transaction_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaction_id` (`transaction_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `transaction_items`
--
ALTER TABLE `transaction_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transaction_items`
--
ALTER TABLE `transaction_items`
  ADD CONSTRAINT `transaction_items_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transaction_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
