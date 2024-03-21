-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 31, 2023 at 11:09 PM
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
-- Database: `secangkir`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(100) NOT NULL,
  `customer_id` int(6) NOT NULL,
  `food_id` int(4) NOT NULL,
  `food_name` varchar(255) NOT NULL,
  `food_price` decimal(10,2) NOT NULL,
  `quantity` int(10) NOT NULL,
  `food_image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cart_id`, `customer_id`, `food_id`, `food_name`, `food_price`, `quantity`, `food_image`) VALUES
(14, 1, 3, 'Fries', 5.00, 1, 'fries.jpg'),
(15, 1, 29, 'Blueberry Waffle', 4.00, 1, 'blueberry_waffle.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `category_image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `category_name`, `category_image`) VALUES
(1, 'Food', 'Food_Category_1.jpg'),
(2, 'Snack', 'Food_Category_2.jpg'),
(3, 'Dessert', 'Food_Category_3.jpg'),
(4, 'Beverages', 'Food_Category_4.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customer_id` int(6) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `email_address` varchar(255) NOT NULL,
  `phone_number` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `registration_date` datetime NOT NULL,
  `updation_date` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customer_id`, `customer_name`, `email_address`, `phone_number`, `password`, `registration_date`, `updation_date`) VALUES
(1, 'Chai Xiao Hui', 'ai210319@student.uthm.edu.my', '01234568520', '123456789', '2023-06-28 08:46:45', '2023-12-31 06:42:08'),
(2, 'User', 'user@gmail.com', '0177609899', '123456789', '2023-06-29 10:15:09', '2023-06-29 10:15:09'),
(3, 'Joey Tan', 'joeytan@gmail.com', '0123897865', 'joeytan123', '2023-06-30 10:30:00', '2023-06-30 10:30:00'),
(4, 'Sazleena', 'sazleena@gmail.com', '0189982238', 'sazleena123', '2023-07-01 14:05:00', '2023-07-01 14:05:00'),
(5, 'John', 'john@gmail.com', '0178907763', 'john123', '2023-07-01 12:05:00', '2023-07-01 12:05:00'),
(6, 'Jeremy', 'jeremy@gmail.com', '0119021562', 'jeremy123', '2023-07-02 15:15:40', '2023-07-02 15:15:40'),
(7, 'Ahmad', 'ahmad@gmail.com', '0162455591', 'ahmad123', '2023-07-03 17:45:11', '2023-07-03 17:45:11'),
(8, 'Hannah', 'hannah@gmail.com', '0172322283', 'hannah123', '2023-07-04 09:32:41', '2023-12-31 06:46:46');

-- --------------------------------------------------------

--
-- Table structure for table `food`
--

CREATE TABLE `food` (
  `food_id` int(6) NOT NULL,
  `category_id` int(11) NOT NULL,
  `food_name` varchar(255) NOT NULL,
  `net_price` decimal(10,2) NOT NULL,
  `food_image` varchar(255) NOT NULL,
  `updation_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `food`
--

INSERT INTO `food` (`food_id`, `category_id`, `food_name`, `net_price`, `food_image`, `updation_date`) VALUES
(1, 2, 'Peanut Waffle', 4.00, 'peanut_waffle.jpg', '2023-07-01 05:09:20'),
(2, 1, 'Tomyam Soup', 5.50, 'tomyam.jpg', '2023-06-18 19:07:50'),
(3, 2, 'Fries', 5.00, 'fries.jpg', '2023-07-01 05:10:18'),
(4, 2, 'Karipap Pusar', 5.00, 'karipap.jpg', '2023-06-18 19:09:10'),
(5, 1, 'Ayam Soup', 4.90, 'ayamsoup.jpg', '2023-07-01 05:15:03'),
(6, 3, 'Biscoff Cheesecake', 8.90, 'biscoff.jpg', '2023-07-01 05:17:02'),
(7, 4, 'Chocolate (Ice)', 7.90, 'chocolate_ice.jpg', '2023-07-01 05:18:01'),
(8, 4, 'Teh Tarik (Ice)', 4.00, 'tehtarik_ice.jpg', '2023-07-01 05:18:31'),
(9, 1, 'Kari Soup', 4.90, 'maggie_kari.jpeg', '2023-07-01 05:19:48'),
(10, 2, 'Chocolate Waffle', 4.00, 'chocolate_waffle.jpg', '2023-07-01 05:20:51'),
(11, 3, 'Carrot Cake', 8.90, 'carrot_cake.png', '2023-07-01 05:22:10'),
(12, 4, 'Americano (Hot)', 4.90, 'americano_hot.jpeg', '2023-07-01 05:23:12'),
(13, 4, 'Americano (Ice)', 5.90, 'americano_ice.jpg', '2023-07-01 05:24:17'),
(14, 1, 'Bihun Soup Pama', 5.50, 'bihun_pama.png', '2023-07-01 06:15:45'),
(15, 2, 'Butter Waffle', 4.00, 'butter_waffle.jpg', '2023-07-01 05:26:12'),
(16, 3, 'Butterscotch Cake', 8.90, 'butterscotch_cake.jpg', '2023-07-01 05:27:17'),
(17, 4, 'Latte (Hot)', 5.90, 'latte_hot.jpg', '2023-07-01 05:28:21'),
(18, 4, 'Latte (Ice)', 6.90, 'latte_ice.jpeg', '2023-07-01 05:29:18'),
(19, 1, 'Ramen Original + Cheese', 9.00, 'ramen_c.jpg', '2023-07-01 05:30:40'),
(20, 2, 'Kaya Waffle', 4.00, 'kaya_waffle.jpg', '2023-07-01 05:32:46'),
(21, 3, 'Indulgence Cake', 8.90, 'indulgence_cake.jpg', '2023-07-01 05:33:49'),
(22, 4, 'Vanilla Latte (Hot)', 6.90, 'vanilla_latte_hot.jpg', '2023-07-01 05:34:49'),
(23, 4, 'Vanilla Latte (Ice)', 7.90, 'vanilla_latte_ice.jpeg', '2023-07-01 05:35:47'),
(24, 1, 'Mee Soup', 1.50, 'mee_soup.jpg', '2023-07-01 05:38:34'),
(25, 1, 'Yee Mee Soup', 1.50, 'yeemee_soup.jpg', '2023-07-01 05:39:27'),
(26, 1, 'Bihun Soup', 1.50, 'bihun_soup.jpg', '2023-07-01 05:40:21'),
(27, 1, 'Ala Carte Item', 1.50, 'alacarte.jpg', '2023-07-01 05:41:08'),
(28, 2, 'Strawberry Waffle', 4.00, 'strawberry_waffle.jpg', '2023-07-01 05:42:11'),
(29, 2, 'Blueberry Waffle', 4.00, 'blueberry_waffle.jpg', '2023-07-01 05:43:29'),
(30, 2, 'Samosa Kentang', 5.00, 'samosa_kentang.jpeg', '2023-07-01 05:44:38'),
(31, 4, 'Hazelnut Latte (Hot)', 6.90, 'hazelnut_latte_hot.jpg', '2023-07-01 05:46:05'),
(32, 4, 'Hazelnut Latte (Ice)', 7.90, 'hazelnut_latte_ice.jpg', '2023-07-01 05:46:55'),
(33, 4, 'Caremal Latte (Hot)', 6.90, 'caramel_lattle_hot.jpg', '2023-07-01 05:48:09'),
(34, 4, 'Caremal Latte (Ice)', 7.90, 'caramel_lattle_ice.jpg', '2023-07-01 05:48:57'),
(35, 4, 'Cappucino (Hot)', 6.90, 'cappucino_hot.jpg', '2023-07-01 05:50:37'),
(36, 4, 'Cappucino (Ice)', 7.90, 'capuccino_ice.jpg', '2023-07-01 05:52:09'),
(37, 4, 'Chocolate (Hot)', 5.90, 'chocolate_hot.jpeg', '2023-07-01 05:53:15'),
(38, 4, 'Vanilla Milkshake', 7.90, 'vanilla_milkshake.jpg', '2023-07-01 05:54:22'),
(39, 4, 'Strawberry Milkshake', 7.90, 'strawberry_milkshake.jpg', '2023-07-01 05:55:34'),
(40, 4, 'Fresh Orange Juice', 7.90, 'fresh_orange.jpg', '2023-07-01 05:57:12'),
(41, 4, 'Green Apple Juice', 7.90, 'green_apple.jpg', '2023-07-01 05:58:02'),
(42, 4, 'Watermelon Juice', 7.90, 'watermelon.jpg', '2023-07-01 05:58:58'),
(43, 4, 'Milo (Hot)', 3.00, 'milo_hot.jpeg', '2023-07-01 06:00:19'),
(44, 4, 'Milo (Ice)', 4.00, 'milo_ice.jpg', '2023-07-01 06:01:15'),
(45, 4, 'Teh Tarik (Hot)', 3.00, 'tehtarik_hot.jpg', '2023-07-01 06:02:24'),
(46, 4, 'A&W Float', 6.00, 'aandw.jpg', '2023-07-01 06:03:17'),
(47, 1, 'Ramen Cheese + Sosej', 10.50, 'ramen_cs.jpeg', '2023-07-01 06:04:17'),
(48, 2, 'Nugget', 5.00, 'nugget.jpg', '2023-07-01 06:05:19'),
(49, 4, 'Mocha (Hot)', 6.90, 'mocha_hot.jpg', '2023-07-01 06:06:23'),
(50, 4, 'Mocha (Ice)', 7.90, 'mocha_ice.jpg', '2023-07-01 06:07:03'),
(51, 1, 'Mee Kari', 9.90, 'mee_kari.jpeg', '2023-07-01 06:08:00'),
(52, 2, 'Crispy Dumpling', 5.00, 'crispy_dumpling.jpg', '2023-07-01 06:09:00'),
(53, 3, 'Russian Cake', 8.90, 'russian_cake.jpg', '2023-07-01 06:10:00'),
(54, 4, 'Chocolate Milkshake', 7.90, 'chocolate_milkshake.jpg', '2023-07-01 06:11:01');

-- --------------------------------------------------------

--
-- Table structure for table `orderitems`
--

CREATE TABLE `orderitems` (
  `order_items_id` int(6) NOT NULL,
  `order_id` int(6) NOT NULL,
  `food_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orderitems`
--

INSERT INTO `orderitems` (`order_items_id`, `order_id`, `food_id`, `quantity`, `subtotal`) VALUES
(1, 1, 3, 2, 10.00),
(2, 1, 2, 1, 5.50),
(3, 1, 8, 1, 4.00),
(4, 2, 3, 2, 10.00),
(5, 2, 4, 1, 5.00),
(6, 3, 4, 2, 10.00),
(7, 4, 7, 4, 31.60),
(8, 5, 4, 5, 25.00),
(9, 6, 5, 1, 7.90),
(10, 6, 7, 1, 7.90),
(11, 7, 8, 1, 4.00),
(12, 7, 6, 2, 17.80),
(13, 8, 1, 3, 12.00),
(14, 9, 7, 2, 15.80),
(15, 10, 1, 1, 4.00),
(16, 10, 2, 1, 5.50),
(17, 10, 4, 3, 15.00),
(18, 10, 3, 1, 5.00),
(19, 11, 7, 3, 23.70),
(20, 13, 1, 1, 4.00),
(21, 14, 2, 3, 16.50),
(22, 15, 7, 3, 23.70),
(23, 15, 5, 3, 23.70),
(24, 16, 1, 1, 4.00),
(25, 17, 8, 2, 8.00),
(26, 17, 7, 1, 7.90),
(27, 18, 5, 1, 4.90);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(6) NOT NULL,
  `customer_id` int(6) NOT NULL,
  `order_status` varchar(255) NOT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `take_meal` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `customer_id`, `order_status`, `remark`, `take_meal`) VALUES
(1, 1, 'complete', NULL, 'Take Away'),
(2, 2, 'complete', NULL, 'Take Away'),
(3, 2, 'complete', NULL, 'Take Away'),
(4, 3, 'complete', NULL, 'Take Away'),
(5, 1, 'complete', NULL, 'Take Away'),
(6, 4, 'complete', NULL, 'Take Away'),
(7, 5, 'complete', 'less sugar', 'Dine In'),
(8, 6, 'complete', 'less ice', 'Take Away'),
(9, 7, 'complete', 'less sugar', 'Dine In'),
(10, 8, 'complete', NULL, 'Take Away'),
(11, 5, 'complete', 'Thank You', 'Dine In'),
(12, 2, 'complete', '', 'Take Away'),
(13, 8, 'complete', '', 'Take Away'),
(14, 6, 'complete', '', 'Dine In'),
(15, 2, 'preparing', '', 'Dine In'),
(16, 8, 'in queue', '', 'Dine In'),
(17, 4, 'in queue', '', 'Dine In'),
(18, 1, 'preparing', '', 'Dine In');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `payment_id` int(6) NOT NULL,
  `order_id` int(6) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `payment_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `paymentMethod_id` int(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`payment_id`, `order_id`, `total_amount`, `payment_time`, `paymentMethod_id`) VALUES
(1, 1, 19.50, '2023-07-01 01:15:13', 1),
(2, 2, 15.00, '2023-07-01 02:52:01', 1),
(3, 3, 10.00, '2023-07-02 02:50:51', 2),
(4, 4, 31.60, '2023-07-02 02:59:00', 2),
(5, 5, 25.00, '2023-07-03 01:42:33', 1),
(6, 6, 15.80, '2023-07-03 06:31:32', 2),
(7, 7, 21.80, '2023-07-04 04:41:18', 1),
(8, 8, 12.00, '2023-07-04 07:51:29', 1),
(9, 9, 15.80, '2023-07-05 09:58:11', 2),
(10, 10, 9.50, '2023-07-05 10:52:09', 2),
(11, 11, 20.00, '2023-07-06 03:11:24', 1),
(12, 12, 23.70, '2023-07-06 06:38:01', 1),
(13, 13, 4.00, '2023-07-07 00:47:07', 1),
(14, 14, 16.50, '2023-07-07 04:48:55', 1),
(15, 15, 47.40, '2023-07-08 03:20:14', 1),
(16, 16, 4.00, '2023-07-08 03:22:54', 1),
(17, 17, 15.90, '2023-07-08 03:27:44', 2),
(18, 18, 4.90, '2023-12-31 21:45:33', 2);

-- --------------------------------------------------------

--
-- Table structure for table `paymentmethod`
--

CREATE TABLE `paymentmethod` (
  `paymentMethod_id` int(6) NOT NULL,
  `paymentMethod_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `paymentmethod`
--

INSERT INTO `paymentmethod` (`paymentMethod_id`, `paymentMethod_name`) VALUES
(1, 'TNG'),
(2, 'Online Banking');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `staff_id` int(4) NOT NULL,
  `staff_name` varchar(255) NOT NULL,
  `staff_password` varchar(255) DEFAULT NULL,
  `staff_contact` varchar(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`staff_id`, `staff_name`, `staff_password`, `staff_contact`) VALUES
(1, 'Ke Xin', 'kexin123', '0125658965');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `wishlist_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `food_id` int(11) DEFAULT NULL,
  `food_name` varchar(255) DEFAULT NULL,
  `net_price` decimal(10,2) DEFAULT NULL,
  `food_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`wishlist_id`, `customer_id`, `food_id`, `food_name`, `net_price`, `food_image`) VALUES
(3, 1, 54, 'Chocolate Milkshake', 7.90, 'chocolate_milkshake.jpg'),
(4, 1, 10, 'Chocolate Waffle', 4.00, 'chocolate_waffle.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `food`
--
ALTER TABLE `food`
  ADD PRIMARY KEY (`food_id`);

--
-- Indexes for table `orderitems`
--
ALTER TABLE `orderitems`
  ADD PRIMARY KEY (`order_items_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `food_id` (`food_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `paymentMethod_id` (`paymentMethod_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `paymentmethod`
--
ALTER TABLE `paymentmethod`
  ADD PRIMARY KEY (`paymentMethod_id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`staff_id`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`wishlist_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customer_id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `orderitems`
--
ALTER TABLE `orderitems`
  MODIFY `order_items_id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `payment_id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `paymentmethod`
--
ALTER TABLE `paymentmethod`
  MODIFY `paymentMethod_id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `staff_id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `wishlist_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orderitems`
--
ALTER TABLE `orderitems`
  ADD CONSTRAINT `orderitems_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `orderitems_ibfk_2` FOREIGN KEY (`food_id`) REFERENCES `food` (`food_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_ibfk_3` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`);

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_3` FOREIGN KEY (`paymentMethod_id`) REFERENCES `paymentmethod` (`paymentMethod_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `payment_ibfk_4` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
