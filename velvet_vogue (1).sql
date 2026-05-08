-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 04, 2026 at 10:31 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `velvet_vogue`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `password`, `email`, `created_at`) VALUES
(1, 'admin', '$2y$10$..B7ZhmBC6Otk3skN4PPOeEj8s7QRIPj2GfWErepiccbcdhj3OX1a', 'admin@velvetvogue.lk', '2025-10-29 11:36:10');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `size` varchar(50) DEFAULT NULL,
  `color` varchar(50) DEFAULT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `quantity`, `size`, `color`, `added_at`) VALUES
(31, 3, 123, 1, 'One Size', 'Gray', '2026-01-25 04:18:27'),
(32, 3, 136, 1, 'One Size', 'Purple', '2026-01-25 04:18:33'),
(37, 1, 137, 1, 'One Size', 'Beige', '2026-01-25 06:31:15'),
(38, 1, 139, 1, 'One Size', 'Gray', '2026-01-25 06:31:19'),
(39, 1, 141, 1, 'M', 'Red', '2026-02-01 08:06:34'),
(40, 1, 147, 1, 'L', 'Green', '2026-02-01 08:07:14');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(3, 'Accessories'),
(1, 'Casual Wear'),
(4, 'Footwear'),
(2, 'Formal Wear'),
(7, 'Kids'),
(5, 'Men'),
(6, 'Women');

-- --------------------------------------------------------

--
-- Table structure for table `inquiries`
--

CREATE TABLE `inquiries` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `status` enum('new','read','replied') DEFAULT 'new',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inquiries`
--

INSERT INTO `inquiries` (`id`, `name`, `email`, `message`, `status`, `created_at`) VALUES
(1, 'Kaveesha Fernando', 'kaveeshaamiru05@gmail.com', 'Hello I have an Issue', 'replied', '2025-11-11 11:34:39'),
(2, 'Sanka Silva', 'sanka123@gmail.com', 'There was an Issue with the Checkout portal need some assistance with it.', 'read', '2026-01-25 05:28:14');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` varchar(50) DEFAULT 'pending',
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `guest_name` varchar(255) DEFAULT NULL,
  `guest_email` varchar(255) DEFAULT NULL,
  `guest_address` varchar(500) DEFAULT NULL,
  `guest_city` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_amount`, `status`, `order_date`, `guest_name`, `guest_email`, `guest_address`, `guest_city`) VALUES
(1, 1, 7200.00, 'completed', '2025-11-12 08:41:02', NULL, NULL, NULL, NULL),
(2, 2, 10800.00, 'completed', '2025-11-12 08:42:09', NULL, NULL, NULL, NULL),
(3, 1, 1500.00, 'completed', '2025-12-11 06:25:07', NULL, NULL, NULL, NULL),
(4, 1, 1400.00, 'completed', '2026-01-23 06:12:43', NULL, NULL, NULL, NULL),
(5, 3, 6000.00, 'delivered', '2026-01-25 04:15:11', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `size` varchar(50) DEFAULT NULL,
  `color` varchar(50) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `size`, `color`, `price`) VALUES
(1, 1, 41, 1, 'L', 'Navy', 3500.00),
(2, 1, 37, 1, 'M', 'Burgundy', 3200.00),
(3, 2, 38, 1, 'L', 'Black', 5800.00),
(4, 2, 39, 1, 'L', 'Navy', 4500.00),
(5, 3, 138, 1, 'One Size', 'Gray', 1000.00),
(6, 4, 137, 1, 'One Size', 'Beige', 900.00),
(7, 5, 130, 1, 'L', 'Green', 3000.00),
(8, 5, 129, 1, 'XXL', 'White', 2500.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `category` varchar(50) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `rating` decimal(3,2) DEFAULT NULL,
  `sizes` varchar(255) DEFAULT NULL,
  `colors` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `category`, `price`, `description`, `image_url`, `rating`, `sizes`, `colors`, `created_at`) VALUES
(1, 'Floral Maxi Dress', 'Women', 6800.00, 'Beautiful floral maxi dress perfect for any occasion', 'https://images.unsplash.com/photo-1595777457583-95e059d581b8?w=500', 4.80, 'S,M,L,XL', 'Black|#000000,White|#FFFFFF,Navy|#001f3f', '2025-10-29 05:33:51'),
(2, 'Leather Biker Jacket', 'Men', 12500.00, 'Premium leather biker jacket with classic style', 'https://images.unsplash.com/photo-1521223890158-f9f7c3d5d504?w=500', 4.90, 'XS,S,M,L,XL,XXL', 'Red|#FF0000,Blue|#0074D9,Green|#2ECC40', '2025-10-29 05:33:51'),
(3, 'Striped Casual Shirt', 'Men', 3200.00, 'Comfortable striped casual shirt for everyday wear', 'https://tse1.mm.bing.net/th/id/OIP._ygAY7wG-9OsJZRuFQv5cwHaJ4?rs=1&pid=ImgDetMain&o=7&rm=3', 4.60, 'S,M,L,XL,XXL', 'Blue|#0074D9,White|#FFFFFF,Gray|#AAAAAA', '2025-10-29 05:33:51'),
(4, 'High Waist Jean', 'Women', 5400.00, 'Stylish high waist jeans with perfect fit', 'https://th.bing.com/th/id/OIP.cTq10Q3EHwVRlQePL3XuxgAAAA?w=186&h=279&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3', 4.70, 'XS,S,M,L,XL', 'Blue|#0074D9,Black|#000000,White|#FFFFFF', '2025-10-29 05:33:51'),
(5, 'Knit Sweater', 'Women', 4900.00, 'Cozy knit sweater perfect for winter', 'https://tse3.mm.bing.net/th/id/OIP.C7IkZtwnNp_sID_CZfhYWQHaJ2?rs=1&pid=ImgDetMain&o=7&rm=3', 4.50, 'XS,S,M,L,XL', 'Beige|#F5F5DC,Pink|#FF69B4,Gray|#808080', '2025-10-29 05:33:51'),
(6, 'Silk Scarf', 'Accessories', 1800.00, 'Elegant silk scarf to complete your look', 'https://styleblueprint.com/wp-content/uploads/2023/02/SB-How-to-Style-Silk-Scarf-French-Style-Shopbop.jpg', 4.80, 'One Size', 'Red|#FF0000,Black|#000000,Blue|#0074D9', '2025-10-29 05:33:51'),
(7, 'Classic White Sneakers', 'Footwear', 7200.00, 'Timeless white sneakers for casual style', 'https://images.unsplash.com/photo-1549298916-b41d501d3772?w=500', 5.00, '6,7,8,9,10,11,12', 'White|#FFFFFF,Black|#000000,Gray|#808080', '2025-10-29 05:33:51'),
(8, 'Cotton T-Shirt', 'Men', 2500.00, 'High quality cotton t-shirt in various colors', 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=500', 4.50, 'S,M,L,XL', 'Black|#000000,Navy|#001f3f,Charcoal|#36454F', '2025-10-29 05:33:51'),
(9, 'Classic Oxford Shirt', 'Men', 4500.00, 'Professional oxford shirt for formal occasions', 'https://tse1.mm.bing.net/th/id/OIP.pTAm9rmGIgl5ismbh-IFIQHaLH?cb=ucfimg2ucfimg=1&rs=1&pid=ImgDetMain&o=7&rm=3', 4.70, 'S,M,L,XL,XXL', 'White|#FFFFFF,Blue|#0074D9,Pink|#FF69B4', '2025-10-29 05:33:51'),
(10, 'Slim Fit Chinos', 'Men', 5200.00, 'Comfortable slim fit chinos for casual or formal wear', 'https://images.unsplash.com/photo-1473966968600-fa801b869a1a?w=500', 4.60, 'S,M,L,XL', 'Khaki|#C3B091,Navy|#001f3f,Gray|#808080', '2025-10-29 05:33:51'),
(11, 'Denim Jacket', 'Men', 8900.00, 'Classic denim jacket that never goes out of style', 'https://tse2.mm.bing.net/th/id/OIP.boBIFDQ5djqnS45E5uEl9QHaHa?rs=1&pid=ImgDetMain&o=7&rm=3', 5.00, 'S,M,L,XL,XXL', 'Blue|#0074D9,Black|#000000,Light Blue|#ADD8E6', '2025-10-29 05:33:51'),
(12, 'Casual Polo Shirt', 'Men', 3800.00, 'Versatile polo shirt perfect for any casual occasion', 'https://images.unsplash.com/photo-1586790170083-2f9ceadc732d?w=500', 4.50, 'S,M,L,XL,XXL', 'White|#FFFFFF,Black|#000000,Navy|#001f3f,Red|#FF0000', '2025-10-29 05:33:51'),
(13, 'Formal Blazer', 'Men', 15000.00, 'Premium formal blazer for professional look', 'https://images.unsplash.com/photo-1507679799987-c73779587ccf?w=500', 4.90, 'S,M,L,XL,XXL', 'Black|#000000,Navy|#001f3f,Charcoal|#36454F', '2025-10-29 05:33:51'),
(14, 'Sports Hoodie', 'Men', 6200.00, 'Comfortable sports hoodie for active lifestyle', 'https://images.unsplash.com/photo-1556821840-3a63f95609a7?w=500', 4.40, 'S,M,L,XL,XXL', 'Black|#000000,Gray|#808080,Navy|#001f3f,Red|#FF0000', '2025-10-29 05:33:51'),
(15, 'Leather Belt', 'Accessories', 2500.00, 'Premium leather belt to accessorize any outfit', 'https://m.media-amazon.com/images/I/71QoU0XJCHL.jpg', 4.40, 'S,M,L', 'Brown|#8B4513,Black|#000000,Tan|#D2B48C', '2025-10-29 05:33:51'),
(16, 'Running Shoes', 'Footwear', 8500.00, 'Professional running shoes for comfort and performance', 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=500', 4.70, '6,7,8,9,10,11,12', 'Black|#000000,Brown|#8B4513,Tan|#D2B48C', '2025-10-29 05:33:51'),
(17, 'Summer Sundress', 'Women', 5800.00, 'Light and breezy sundress perfect for warm days', 'https://images.unsplash.com/photo-1572804013309-59a88b7e92f1?w=500', 4.70, 'XS,S,M,L,XL', 'Yellow|#FFFF00,Pink|#FF69B4,White|#FFFFFF', '2025-10-29 07:54:56'),
(18, 'Denim Jacket', 'Women', 8900.00, 'Classic denim jacket for layering', 'https://tse3.mm.bing.net/th/id/OIP.TwWHq12PmQqADEfVbxBT4AHaKX?rs=1&pid=ImgDetMain&o=7&rm=3', 4.80, 'XS,S,M,L,XL', 'Blue|#0074D9,Black|#000000,Light Blue|#ADD8E6', '2025-10-29 07:54:56'),
(19, 'Evening Gown', 'Women', 18500.00, 'Elegant gown for special occasions', 'https://tse3.mm.bing.net/th/id/OIP.leHzFnhoEgRayguNMplX1AHaLH?rs=1&pid=ImgDetMain&o=7&rm=3', 4.90, 'XS,S,M,L,XL', 'Red|#FF0000,Black|#000000,Navy|#001f3f', '2025-10-29 07:54:56'),
(20, 'Yoga Pants', 'Women', 3200.00, 'Stretchy yoga pants with moisture-wicking fabric', 'https://litb-cgis.rightinthebox.com/images/640x640/201805/isbf1527659295043.jpg', 4.60, 'XS,S,M,L,XL', 'Black|#000000,Gray|#808080,Navy|#001f3f', '2025-10-29 07:54:56'),
(21, 'Blazer Jacket', 'Women', 12500.00, 'Professional blazer for the office', 'https://th.bing.com/th/id/OIP.fTNs5Zdp_zwnwNrW74p43QHaJQ?w=191&h=239&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3', 4.70, 'XS,S,M,L,XL', 'Black|#000000,Gray|#808080,Navy|#001f3f', '2025-10-29 07:54:56'),
(22, 'Maxi Skirt', 'Women', 4200.00, 'Flowing maxi skirt for elegant casual wear', 'https://tse2.mm.bing.net/th/id/OIP.JD2BqGxptkSSxvYLRt3fcwHaIb?rs=1&pid=ImgDetMain&o=7&rm=3', 4.50, 'XS,S,M,L,XL', 'Black|#000000,Navy|#001f3f,Burgundy|#800020', '2025-10-29 07:54:56'),
(23, 'Cardigan Sweater', 'Women', 5600.00, 'Comfortable cardigan for layering', 'https://tse2.mm.bing.net/th/id/OIP.sRpVBSqAH3KMvIdfZgUHHwHaHa?rs=1&pid=ImgDetMain&o=7&rm=3', 4.60, 'XS,S,M,L,XL', 'Beige|#F5F5DC,Gray|#808080,Pink|#FF69B4', '2025-10-29 07:54:56'),
(24, 'Tank Top Pack', 'Women', 2400.00, 'Set of 3 essential tank tops', 'https://i5.walmartimages.com/seo/NELEUS-Womens-Tight-Fitting-Base-Layer-Dry-Fit-Tank-Top-3-Pack-Blackish-Green-Purple-Pink-US-Size-XL_3e034d9a-00d0-4576-b91b-aad01eb040e1.38cc188e5be2037a15f580ddaf5fbd67.jpeg', 4.40, 'XS,S,M,L,XL', 'Black|#000000,White|#FFFFFF,Gray|#808080', '2025-10-29 07:54:56'),
(25, 'Pencil Skirt', 'Women', 3800.00, 'Classic pencil skirt for work', 'https://media.very.co.uk/i/very/W7LA1_SQ1_0000000004_BLACK_MDf?fmt=auto', 4.50, 'XS,S,M,L,XL', 'Black|#000000,Navy|#001f3f,Gray|#808080', '2025-10-29 07:54:56'),
(26, 'Wide Leg Trousers', 'Women', 5900.00, 'Trendy wide leg pants for modern style', 'https://tse4.mm.bing.net/th/id/OIP.FkgfiLu_6yiQaVY8fFav6AHaJ4?rs=1&pid=ImgDetMain&o=7&rm=3', 4.70, 'XS,S,M,L,XL', 'Black|#000000,White|#FFFFFF,Beige|#F5F5DC', '2025-10-29 07:54:56'),
(27, 'Silk Blouse', 'Women', 6800.00, 'Luxurious silk blouse for elegant occasions', 'https://th.bing.com/th/id/R.91b5ecbb36184df3548aa0e6c051aec4?rik=m%2fPTmZWGTGFTbg&pid=ImgRaw&r=0', 4.80, 'XS,S,M,L,XL', 'White|#FFFFFF,Pink|#FF69B4,Cream|#FFFDD0', '2025-10-29 07:54:56'),
(28, 'Jumpsuit', 'Women', 7200.00, 'Stylish one-piece jumpsuit', 'https://th.bing.com/th/id/OIP.LOYt_wH8ytnoNAoeWK2TfwHaJ3?w=186&h=248&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3', 4.60, 'XS,S,M,L,XL', 'Black|#000000,Navy|#001f3f,Red|#FF0000', '2025-10-29 07:54:56'),
(29, 'Trench Coat', 'Women', 14500.00, 'Classic trench coat for all seasons', 'https://images.unsplash.com/photo-1539533018447-63fcce2678e3?w=500', 4.80, 'XS,S,M,L,XL', 'Beige|#F5F5DC,Black|#000000,Navy|#001f3f', '2025-10-29 07:54:56'),
(30, 'Midi Dress', 'Women', 6200.00, 'Versatile midi dress for day or night', 'https://th.bing.com/th/id/OIP.fAUleREmlPCUsZUposkrKAHaLH?w=186&h=279&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3', 4.50, 'XS,S,M,L,XL', 'Black|#000000,Navy|#001f3f,Burgundy|#800020', '2025-10-29 07:54:56'),
(31, 'Wrap Dress', 'Women', 5500.00, 'Flattering wrap dress design', 'https://th.bing.com/th/id/R.b080b23adbe6377de66ef456754a3f46?rik=h%2fGgMLE21dQnGA&riu=http%3a%2f%2fnatalet.com%2fimages5%2f0516%2fwrap-dresses-for-women%2fwrap-dresses-for-women-71_3.jpg&ehk=Zde%2b3LKoOyUgECoczDLc5MkaaNAVhe5RTKOSyawzJL8%3d&risl=&pid=ImgRa', 4.60, 'XS,S,M,L,XL', 'Black|#000000,Red|#FF0000,Navy|#001f3f', '2025-10-29 07:54:56'),
(32, 'Puffer Jacket', 'Women', 9800.00, 'Warm puffer jacket for winter', 'https://i5.walmartimages.com/asr/ed5be7f4-7926-4ff4-bca1-330ae9744960_1.069992eeb6d02195e3ae84a00e6d37b0.jpeg', 4.70, 'XS,S,M,L,XL', 'Black|#000000,Navy|#001f3f,Pink|#FF69B4', '2025-10-29 07:54:56'),
(33, 'Crop Top', 'Women', 2800.00, 'Trendy crop top for casual wear', 'https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?w=500', 4.40, 'XS,S,M,L', 'White|#FFFFFF,Black|#000000,Pink|#FF69B4', '2025-10-29 07:54:56'),
(34, 'Palazzo Pants', 'Women', 4800.00, 'Comfortable palazzo pants with elegant drape', 'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?w=500', 4.50, 'XS,S,M,L,XL', 'Black|#000000,Navy|#001f3f,White|#FFFFFF', '2025-10-29 07:54:56'),
(35, 'Tunic Top', 'Women', 3900.00, 'Loose-fitting tunic for comfort', 'https://images.unsplash.com/photo-1581338834647-b0fb40704e21?w=500', 4.80, 'XS,S,M,L,XL', 'White|#FFFFFF,Gray|#808080,Beige|#F5F5DC', '2025-10-29 07:54:56'),
(36, 'A-Line Skirt', 'Women', 4100.00, 'Classic A-line skirt silhouette', 'https://i5.walmartimages.com/asr/a8ddc762-70f4-4392-8723-cd6e5b40f0b2.8cc8d1abf89dd807bb9f9f3c9ca3702a.jpeg', 4.50, 'XS,S,M,L,XL', 'Black|#000000,Navy|#001f3f,Red|#FF0000', '2025-10-29 07:54:56'),
(37, 'Henley Shirt', 'Men', 3200.00, 'Casual henley shirt with button placket', 'https://th.bing.com/th/id/R.eb600c187ed6782da6d65c628b676d66?rik=M7eobtA0C%2bnM8Q&pid=ImgRaw&r=0', 4.60, 'S,M,L,XL', 'Black|#000000,Navy|#001f3f', '2025-10-29 07:54:57'),
(38, 'Cargo Pants', 'Men', 5800.00, 'Practical cargo pants with multiple pockets', 'https://images.unsplash.com/photo-1624378439575-d8705ad7ae80?w=500', 4.60, 'S,M,L,XL,XXL', 'Khaki|#C3B091,Black|#000000,Olive|#808000', '2025-10-29 07:54:57'),
(39, 'Hoodie', 'Men', 4500.00, 'Comfortable pullover hoodie for casual wear', 'https://th.bing.com/th/id/R.e373b7b15be0ce16159721b8f7f7994a?rik=OlJnD5FOWPrVEA&pid=ImgRaw&r=0', 4.70, 'S,M,L,XL,XXL', 'Black|#000000,Gray|#808080,Navy|#001f3f', '2025-10-29 07:54:57'),
(40, 'Bomber Jacket', 'Men', 8900.00, 'Stylish bomber jacket for cool weather', 'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?w=500', 4.80, 'S,M,L,XL', 'Black|#000000,Green|#2ECC40,Navy|#001f3f', '2025-10-29 07:54:57'),
(41, 'Chino Shorts', 'Men', 3500.00, 'Summer chino shorts in various colors', 'https://tse1.mm.bing.net/th/id/OIP.A90h_ULOkRU6iza7GF_ZBwHaJ4?rs=1&pid=ImgDetMain&o=7&rm=3', 4.40, 'S,M,L,XL', 'Khaki|#C3B091,Navy|#001f3f,Gray|#808080', '2025-10-29 07:54:57'),
(42, 'V-Neck Sweater', 'Men', 5200.00, 'Classic v-neck sweater for layering', 'https://images.unsplash.com/photo-1620799140408-edc6dcb6d633?w=500', 4.60, 'S,M,L,XL,XXL', 'Navy|#001f3f,Gray|#808080,Black|#000000', '2025-10-29 07:54:57'),
(43, 'Track Pants', 'Women', 3800.00, 'Athletic track pants for sports and leisure', 'https://www.lifestylesports.com/on/demandware.static/-/Sites-LSS_eCommerce_Master/default/dwd58dd024/images/61812313xlarge.jpg', 4.50, 'XS,S,M,L,XL', 'Black|#000000,Navy|#001f3f,Gray|#808080', '2025-10-29 07:54:57'),
(44, 'Zip-Up Jacket', 'Men', 6200.00, 'Versatile zip-up jacket for any occasion', 'https://m.media-amazon.com/images/I/71XV+nrPidL._AC_SL1500_.jpg', 4.60, 'S,M,L,XL', 'Black|#000000,Navy|#001f3f,Gray|#808080', '2025-10-29 07:54:57'),
(45, 'Graphic Tee', 'Men', 2200.00, 'Trendy graphic t-shirt with cool design', 'https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?w=500', 4.40, 'S,M,L,XL', 'Black|#000000,White|#FFFFFF,Gray|#808080', '2025-10-29 07:54:57'),
(46, 'Flannel Shirt', 'Men', 4200.00, 'Comfortable flannel shirt for casual style', 'https://tse2.mm.bing.net/th/id/OIP.u7hZ_9zihfxfOR-TwoWN8gAAAA?rs=1&pid=ImgDetMain&o=7&rm=3', 4.50, 'S,M,L,XL,XXL', 'Red|#FF0000,Blue|#0074D9,Black|#000000', '2025-10-29 07:54:57'),
(47, 'Peacoat', 'Women', 12500.00, 'Classic peacoat for formal cold weather', 'https://th.bing.com/th/id/R.b242a7662a88c558adb988dba7006e0a?rik=2f%2brhoQaHgKe9g&pid=ImgRaw&r=0', 4.80, 'XS,S,M,L,XL', 'Black|#000000,Navy|#001f3f,Gray|#808080', '2025-10-29 07:54:57'),
(48, 'Athletic Shorts', 'Men', 2800.00, 'Lightweight shorts for workouts', 'https://images.unsplash.com/photo-1591195853828-11db59a44f6b?w=500', 4.40, 'S,M,L,XL', 'Black|#000000,Gray|#808080', '2025-10-29 07:54:57'),
(49, 'Turtleneck Sweater', 'Men', 5800.00, 'Warm turtleneck for winter style', 'https://images.unsplash.com/photo-1620799140408-edc6dcb6d633?w=500', 4.60, 'S,M,L,XL,XXL', 'Black|#000000,Gray|#808080,Navy|#001f3f', '2025-10-29 07:54:57'),
(50, 'Windbreaker', 'Men', 5500.00, 'Light windbreaker for outdoor activities', 'https://images.unsplash.com/photo-1525450824786-227cbef70703?w=500', 4.50, 'S,M,L,XL', 'Black|#000000,Navy|#001f3f,Red|#FF0000', '2025-10-29 07:54:57'),
(51, 'Jogger Pants', 'Women', 4200.00, 'Comfortable joggers with tapered fit', 'https://th.bing.com/th/id/OIP.kU4qnzFw5xQPeFxOAl28bAHaJ4?o=7rm=3&rs=1&pid=ImgDetMain&o=7&rm=3', 4.60, 'XS,S,M,L,XL', 'Black|#000000,Gray|#808080,Pink|#FF69B4', '2025-10-29 07:54:57'),
(52, 'Plaid Shirt', 'Men', 3800.00, 'Classic plaid button-down shirt', 'https://tse3.mm.bing.net/th/id/OIP.dGLbnKlQfjwCykYFrqMeIAHaJ4?rs=1&pid=ImgDetMain&o=7&rm=3', 4.70, 'S,M,L,XL,XXL', 'Red|#FF0000,Blue|#0074D9,Green|#2ECC40', '2025-10-29 07:54:57'),
(53, 'Parka Jacket', 'Men', 14500.00, 'Heavy-duty parka for extreme cold', 'https://tse3.mm.bing.net/th/id/OIP.zpViL560k_kFivRC0ADH4gHaJ4?rs=1&pid=ImgDetMain&o=7&rm=3', 4.80, 'S,M,L,XL,XXL', 'Black|#000000,Navy|#001f3f,Olive|#808000', '2025-10-29 07:54:57'),
(54, 'Muscle Fit Tee', 'Men', 2400.00, 'Fitted t-shirt for athletic build', 'https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?w=500', 4.40, 'S,M,L,XL', 'Black|#000000,White|#FFFFFF,Gray|#808080', '2025-10-29 07:54:57'),
(55, 'Cardigan', 'Men', 5600.00, 'Button-up cardigan for smart casual', 'https://images.unsplash.com/photo-1434389677669-e08b4cac3105?w=500', 4.60, 'S,M,L,XL,XXL', 'Navy|#001f3f,Gray|#808080,Black|#000000', '2025-10-29 07:54:57'),
(56, 'Sweatpants', 'Men', 3600.00, 'Relaxed fit sweatpants for lounging', 'https://th.bing.com/th/id/OIP.GlXLUc_uW7uNK7FXt6sXvQHaJ4?w=186&h=248&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3', 4.50, 'S,M,L,XL,XXL', 'Black|#000000,Gray|#808080,Navy|#001f3f', '2025-10-29 07:54:57'),
(57, 'Kids Denim Overalls', 'Kids', 3500.00, 'Durable denim overalls for active kids', 'https://tse1.mm.bing.net/th/id/OIP.lMysx1lzUXrpZ0XFELC8HAHaHa?rs=1&pid=ImgDetMain&o=7&rm=3', 4.70, '2T,3T,4T,5T', 'Blue|#0074D9,Black|#000000,Light Blue|#ADD8E6', '2025-10-29 07:54:57'),
(58, 'Cartoon Character Tee', 'Kids', 1500.00, 'Fun t-shirt with favorite cartoon characters', 'https://tse2.mm.bing.net/th/id/OIP.LQkMOXjzdlwYve3ryUDr2gHaHa?rs=1&pid=ImgDetMain&o=7&rm=3', 4.80, '2T,3T,4T,5T', 'Red|#FF0000,Blue|#0074D9,Yellow|#FFFF00', '2025-10-29 07:54:57'),
(59, 'Kids Hoodie', 'Kids', 2800.00, 'Cozy hoodie for children', 'https://th.bing.com/th/id/OIP.vsDeYPOf35rGFAzbg9GmSwHaKR?w=127&h=180&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3', 4.60, '2T,3T,4T,5T', 'Black|#000000,Gray|#808080,Navy|#001f3f', '2025-10-29 07:54:57'),
(61, 'Kids Rain Jacket', 'Kids', 3200.00, 'Waterproof jacket for rainy days', 'https://th.bing.com/th/id/OIP.bFJGhiVb3M45GKxi7Gb9OgHaJm?w=141&h=183&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3', 4.60, '2T,3T,4T,5T', 'Yellow|#FFFF00,Blue|#0074D9,Red|#FF0000', '2025-10-29 07:54:57'),
(62, 'Tutu Dress', 'Kids', 3800.00, 'Princess-style tutu dress for girls', 'https://i.etsystatic.com/14572646/r/il/99e902/1935242410/il_fullxfull.1935242410_lmtr.jpg', 4.70, '2T,3T,4T,5T', 'Pink|#FF69B4,Purple|#9333ea,White|#FFFFFF', '2025-10-29 07:54:57'),
(63, 'Kids Joggers', 'Kids', 2400.00, 'Comfortable joggers for playtime', 'https://static.nike.com/a/images/c_limit,w_592,f_auto/t_product_v1/7c422d76-3503-4f8b-9111-9e6788b4ae9f/K+NIKE+AIR+FLC+JOGGER+-PD.png', 4.80, '2T,3T,4T,5T', 'Black|#000000,Gray|#808080,Navy|#001f3f', '2025-10-29 07:54:57'),
(64, 'Pajama Set', 'Kids', 2800.00, 'Soft pajamas for good night sleep', 'https://tse1.mm.bing.net/th/id/OIP.Xe61jwWJ7Eo6qanGDuVFvgHaHa?rs=1&pid=ImgDetMain&o=7&rm=3', 4.60, '2T,3T,4T,5T', 'Blue|#0074D9,Pink|#FF69B4,White|#FFFFFF', '2025-10-29 07:54:57'),
(65, 'Kids Swimsuit', 'Kids', 2200.00, 'Colorful swimsuit for pool and beach', 'https://th.bing.com/th/id/OIP.KyGPk7wW4lmu5wmdzAsPvAHaJ4?w=186&h=248&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3', 4.50, '2T,3T,4T,5T', 'Pink|#FF69B4,Blue|#0074D9,Green|#2ECC40', '2025-10-29 07:54:57'),
(66, 'Striped Polo Shirt', 'Kids', 1800.00, 'Classic striped polo for kids', 'https://i5.walmartimages.com/seo/Boys-Polo-Uniform-Shirts-Long-Sleeve-Kids-Striped-Polo-Casual-Collared-Polo-Shirts-Fashion-Shirts-Polo-Rugby-Shirt-for-Kids-8-9-Years_63ef9fbd-c147-4be4-baca-ec108507d501.1a245b153120c9c8debe4f73e1ee93e0.jpeg', 4.40, '2T,3T,4T,5T', 'White|#FFFFFF,Navy|#001f3f,Red|#FF0000', '2025-10-29 07:54:57'),
(67, 'Kids Cargo Shorts', 'Kids', 2000.00, 'Practical shorts with pockets', 'https://tse2.mm.bing.net/th/id/OIP.3QTKTHi_CsiKdk82Mnd3rAHaHa?rs=1&pid=ImgDetMain&o=7&rm=3', 4.50, '2T,3T,4T,5T', 'Khaki|#C3B091,Navy|#001f3f,Black|#000000', '2025-10-29 07:54:57'),
(68, 'Winter Coat', 'Kids', 5500.00, 'Warm winter coat for cold weather', 'https://tse3.mm.bing.net/th/id/OIP.5tmG5Xy-aWlLe_N3ThjiswHaHa?rs=1&pid=ImgDetMain&o=7&rm=3', 4.70, '2T,3T,4T,5T', 'Black|#000000,Red|#FF0000,Navy|#001f3f', '2025-10-29 07:54:57'),
(69, 'Kids Leggings', 'Kids', 1600.00, 'Stretchy leggings for girls', 'https://th.bing.com/th/id/OIP.AGS7KIxx3EYHBZkU9ahhhgHaHa?w=202&h=202&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3', 4.80, '2T,3T,4T,5T', 'Black|#000000,Pink|#FF69B4,Gray|#808080', '2025-10-29 07:54:57'),
(70, 'Graphic Sweatshirt', 'Kids', 2600.00, 'Fun sweatshirt with colorful graphics', 'https://tse2.mm.bing.net/th/id/OIP.SuLC0FPIUnMWOZmnurqX8QHaJ4?rs=1&pid=ImgDetMain&o=7&rm=3', 4.50, '2T,3T,4T,5T', 'Red|#FF0000,Blue|#0074D9,Green|#2ECC40', '2025-10-29 07:54:57'),
(71, 'Kids Track Suit', 'Kids', 3800.00, 'Two-piece athletic suit for kids', 'https://assets.adidas.com/images/w_1880,f_auto,q_auto/0063ca74e9b04e4ea3ab8ebf280fa4d5_9366/IX7624_21_model.jpg', 4.90, '2T,3T,4T,5T', 'Black|#000000,Red|#FF0000,Navy|#001f3f', '2025-10-29 07:54:57'),
(72, 'Party Dress', 'Kids', 4500.00, 'Fancy dress for special occasions', 'https://i5.walmartimages.com/seo/Herrnalise-2-10T-Girls-Flower-Dress-Ball-Gown-Party-Pageant-Wedding-Birthday-Floral-Dresses-Kids-Formal-Special-Occasion-Party-Dresses_99cac22e-50eb-43f9-862f-db7506978c30.98cc784b748675021b785e1116e4e7de.jpeg', 4.50, '2T,3T,4T,5T', 'White|#FFFFFF,Pink|#FF69B4,Purple|#9333ea', '2025-10-29 07:54:57'),
(73, 'Kids Baseball Cap', 'Kids', 1200.00, 'Adjustable cap for sun protection', 'https://images.unsplash.com/photo-1588850561407-ed78c282e89b?w=500', 4.40, 'One Size', 'Red|#FF0000,Blue|#0074D9,Black|#000000', '2025-10-29 07:54:57'),
(74, 'Kids Fleece Jacket', 'Kids', 3200.00, 'Soft fleece jacket for layering', 'https://tse1.mm.bing.net/th/id/OIP.1gkB_7WMiJvftXI94cEwjQHaJM?rs=1&pid=ImgDetMain&o=7&rm=3', 4.60, '2T,3T,4T,5T', 'Black|#000000,Navy|#001f3f,Pink|#FF69B4', '2025-10-29 07:54:57'),
(75, 'Denim Jacket Kids', 'Kids', 3500.00, 'Miniature denim jacket for kids', 'https://th.bing.com/th/id/OIP.arFDn17HvZqVPSBuy8VERwHaJ4?w=186&h=248&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3', 4.50, '2T,3T,4T,5T', 'Blue|#0074D9,Black|#000000,Light Blue|#ADD8E6', '2025-10-29 07:54:57'),
(76, 'Kids Dress Pants', 'Kids', 2800.00, 'Formal pants for special events', 'https://i5.walmartimages.com/seo/BXINGOHAI-Kids-Boys-Girls-Dress-Pants-Toddler-Solid-School-Unform-Pants-Slim-Fit-Elastic-Long-Nylon-Pants-With-Pockets-Fall-Savings-Khaki-4-Years_bbac0c09-78b3-46ec-a770-a4b9d89a1fd0.bff88c4bc5c882aa26c79ed42d758309.jpeg', 4.70, '6,7,8,10,12', 'Black|#000000,Gray|#808080,Navy|#001f3f', '2025-10-29 07:54:57'),
(77, 'Wool Scarf', 'Accessories', 1600.00, 'Warm wool scarf for winter', 'https://images.unsplash.com/photo-1520903920243-00d872a2d1c9?w=500', 4.50, 'One Size', 'Gray|#808080,Black|#000000,Beige|#F5F5DC', '2025-10-29 07:54:57'),
(78, 'Crossbody Bag', 'Accessories', 4500.00, 'Stylish crossbody bag for daily use', 'https://images.unsplash.com/photo-1590874103328-eac38a683ce7?w=500', 4.70, 'One Size', 'Black|#000000,Brown|#8B4513,Beige|#F5F5DC', '2025-10-29 07:54:57'),
(79, 'Sunglasses', 'Accessories', 2800.00, 'UV protection sunglasses with polarized lenses', 'https://images.unsplash.com/photo-1511499767150-a48a237f0083?w=500', 4.60, 'One Size', 'Black|#000000,Brown|#8B4513,Gold|#FFD700', '2025-10-29 07:54:57'),
(80, 'Baseball Cap', 'Accessories', 1500.00, 'Adjustable baseball cap in various colors', 'https://tse1.mm.bing.net/th/id/OIP.i9prxU_o9GME98h9PsBXggHaHa?rs=1&pid=ImgDetMain&o=7&rm=3', 4.40, 'One Size', 'Black|#000000,Navy|#001f3f,Red|#FF0000', '2025-10-29 07:54:57'),
(81, 'Tote Bag', 'Accessories', 3200.00, 'Spacious tote bag for shopping', 'https://th.bing.com/th/id/R.ed4244d175b02917462637d465d42150?rik=TJ5jnDyS1bUnkw&pid=ImgRaw&r=0', 4.50, 'One Size', 'Beige|#F5F5DC,Black|#000000,Navy|#001f3f', '2025-10-29 07:54:57'),
(82, 'Beanie Hat', 'Accessories', 1200.00, 'Cozy beanie for cold weather', 'https://images.unsplash.com/photo-1576871337632-b9aef4c17ab9?w=500', 4.50, 'One Size', 'Black|#000000,Gray|#808080,Red|#FF0000', '2025-10-29 07:54:57'),
(83, 'Backpack', 'Accessories', 5500.00, 'Durable backpack with laptop compartment', 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=500', 4.70, 'One Size', 'Black|#000000,Gray|#808080,Navy|#001f3f', '2025-10-29 07:54:57'),
(84, 'Watch', 'Accessories', 8900.00, 'Classic analog watch with leather strap', 'https://images.unsplash.com/photo-1524805444758-089113d48a6d?w=500', 4.80, 'One Size', 'Silver|#C0C0C0,Gold|#FFD700,Black|#000000', '2025-10-29 07:54:57'),
(85, 'Wallet', 'Accessories', 2200.00, 'Genuine leather wallet with card slots', 'https://tse1.mm.bing.net/th/id/OIP.TKvHuNcn24eQ4p38yrDxaQHaGY?rs=1&pid=ImgDetMain&o=7&rm=3', 4.60, 'One Size', 'Black|#000000,Brown|#8B4513,Tan|#D2B48C', '2025-10-29 07:54:57'),
(86, 'Fedora Hat', 'Accessories', 2400.00, 'Stylish fedora hat for fashion statement', 'https://th.bing.com/th/id/OIP.Po-keKVaYA4T995lC6rEJgHaE7?w=251&h=180&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3', 4.50, 'One Size', 'Black|#000000,Brown|#8B4513,Gray|#808080', '2025-10-29 07:54:57'),
(87, 'Clutch Purse', 'Accessories', 3500.00, 'Elegant clutch for evening events', 'https://images.unsplash.com/photo-1564422170194-896b89110ef8?w=500', 4.60, 'One Size', 'Black|#000000', '2025-10-29 07:54:57'),
(88, 'Necktie', 'Accessories', 1800.00, 'Silk necktie for formal occasions', 'https://i5.walmartimages.com/seo/Spring-Notion-Boys-Pre-tied-Woven-Zipper-Tie-Medium-Turquoise-Dotted_38623d61-1458-45ca-a492-5f4beb64f72f_1.7361de52eb404d2755cfbde3b34dc5b0.jpeg', 4.50, 'One Size', 'Navy|#001f3f,Black|#000000,Red|#FF0000', '2025-10-29 07:54:57'),
(89, 'Shoulder Bag', 'Accessories', 4800.00, 'Comfortable shoulder bag with adjustable strap', 'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?w=500', 4.70, 'One Size', 'Black|#000000,Brown|#8B4513,Beige|#F5F5DC', '2025-10-29 07:54:57'),
(90, 'Gloves', 'Accessories', 1400.00, 'Warm gloves for winter protection', 'https://images.unsplash.com/photo-1520903920243-00d872a2d1c9?w=500', 4.40, 'S,M,L', 'Black|#000000,Gray|#808080,Brown|#8B4513', '2025-10-29 07:54:57'),
(91, 'Bow Tie', 'Accessories', 1600.00, 'Pre-tied bow tie for formal wear', 'https://eu-images.contentstack.com/v3/assets/blt7dcd2cfbc90d45de/blt9feb42f2b9177967/60dbb53063584e0ecae44e85/5_320_1.jpg', 4.50, 'One Size', 'Black|#000000,Navy|#001f3f,Red|#FF0000', '2025-10-29 07:54:57'),
(92, 'Bucket Hat', 'Accessories', 1600.00, 'Trendy bucket hat for casual style', 'https://images.unsplash.com/photo-1576871337632-b9aef4c17ab9?w=500', 4.40, 'One Size', 'Black|#000000,Beige|#F5F5DC,Navy|#001f3f', '2025-10-29 07:54:57'),
(93, 'Messenger Bag', 'Accessories', 5200.00, 'Professional messenger bag for work', 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=500', 4.70, 'One Size', 'Brown|#8B4513,Black|#000000,Gray|#808080', '2025-10-29 07:54:57'),
(94, 'Hair Accessories Set', 'Accessories', 1000.00, 'Assorted hair clips and bands', 'https://kidzline.lk/wp-content/uploads/2023/02/hairrr.jpg', 4.30, 'One Size', 'Black|#000000,Pink|#FF69B4', '2025-10-29 07:54:57'),
(95, 'Suspenders', 'Accessories', 1800.00, 'Adjustable suspenders for formal look', 'https://cdn.shopify.com/s/files/1/0296/9753/files/2_787502a5-5363-42ff-86c7-abfbd641461c_1024x1024.png?v=1577901507', 4.40, 'One Size', 'Black|#000000,Navy|#001f3f,Brown|#8B4513', '2025-10-29 07:54:57'),
(96, 'Bandana', 'Accessories', 800.00, 'Cotton bandana in various patterns', 'https://i.pinimg.com/736x/fd/eb/cf/fdebcf985cc3f66d24ebdb00d4362cf1.jpg', 4.30, 'One Size', 'Red|#FF0000,Black|#000000,Blue|#0074D9', '2025-10-29 07:54:57'),
(97, 'Ankle Boots', 'Footwear', 7500.00, 'Stylish ankle boots for all seasons', 'https://i5.walmartimages.com/asr/4642cac1-254a-4354-bf71-fac892103687.c5a1e382849ac445f2fce947adb71d2a.jpeg', 4.70, '6,7,8,9,10,11', 'Black|#000000,Brown|#8B4513,Tan|#D2B48C', '2025-10-29 07:54:57'),
(98, 'Flip Flops', 'Footwear', 1200.00, 'Comfortable flip flops for beach', 'https://tse1.mm.bing.net/th/id/OIP.F7sZR-3WtULQ9dCd4A9LXwHaHa?rs=1&pid=ImgDetMain&o=7&rm=3', 4.40, '6,7,8,9,10,11,12', 'Black|#000000,Blue|#0074D9,Red|#FF0000', '2025-10-29 07:54:57'),
(99, 'Loafers', 'Footwear', 6200.00, 'Classic loafers for casual elegance', 'https://tse1.mm.bing.net/th/id/OIP.D1Ft5V9EBtXXxL7CbkEIRQHaGL?rs=1&pid=ImgDetMain&o=7&rm=3', 4.60, '6,7,8,9,10,11,12', 'Brown|#8B4513,Black|#000000,Navy|#001f3f', '2025-10-29 07:54:57'),
(100, 'High Heels', 'Footwear', 8500.00, 'Elegant high heels for formal events', 'https://images.unsplash.com/photo-1543163521-1bf539c55dd2?w=500', 4.70, '6,7,8,9,10,11', 'Black|#000000,Red|#FF0000,Nude|#E3BC9A', '2025-10-29 07:54:57'),
(101, 'Sandals', 'Footwear', 3500.00, 'Comfortable sandals for summer', 'https://images-cdn.ubuy.co.in/6413d244ef4a5244ea6609f4-crocs-classic-all-terrain-sandal-black.jpg', 4.50, '6,7,8,9,10,11,12', 'Brown|#8B4513,Black|#000000,Tan|#D2B48C', '2025-10-29 07:54:57'),
(102, 'Boots', 'Footwear', 9500.00, 'Durable boots for outdoor activities', 'https://images.unsplash.com/photo-1608256246200-53e635b5b65f?w=500', 4.80, '6,7,8,9,10,11,12', 'Black|#000000,Brown|#8B4513', '2025-10-29 07:54:57'),
(103, 'Slip-On Shoes', 'Footwear', 4500.00, 'Easy slip-on shoes for convenience', 'https://images.unsplash.com/photo-1533867617858-e7b97e060509?w=500', 4.50, '6,7,8,9,10,11,12', 'Black|#000000,Gray|#808080,Navy|#001f3f', '2025-10-29 07:54:57'),
(104, 'Ballet Flats', 'Footwear', 3800.00, 'Comfortable ballet flats for daily wear', 'https://tse1.mm.bing.net/th/id/OIP.qIoCW9KsckImT5skc72w0QHaJ4?rs=1&pid=ImgDetMain&o=7&rm=3', 4.60, '6,7,8,9,10,11', 'Black|#000000,Nude|#E3BC9A,Red|#FF0000', '2025-10-29 07:54:57'),
(105, 'Hiking Boots', 'Footwear', 11500.00, 'Rugged hiking boots for trails', 'https://tse4.mm.bing.net/th/id/OIP.xpVp2UC9IP7cQW6KoK_fvwHaHa?rs=1&pid=ImgDetMain&o=7&rm=3', 4.90, '6,7,8,9,10,11,12', 'Brown|#8B4513,Black|#000000,Gray|#808080', '2025-10-29 07:54:57'),
(106, 'Espadrilles', 'Footwear', 4200.00, 'Summer espadrilles with rope sole', 'https://tse2.mm.bing.net/th/id/OIP.7LCKWP0sPH4UIW3LRj6_6gHaH9?rs=1&pid=ImgDetMain&o=7&rm=3', 4.50, '6,7,8,9,10,11', 'Beige|#F5F5DC,Black|#000000,Navy|#001f3f', '2025-10-29 07:54:57'),
(107, 'Dress Shoes', 'Footwear', 8900.00, 'Formal dress shoes for business', 'https://images.unsplash.com/photo-1533867617858-e7b97e060509?w=500', 4.70, '6,7,8,9,10,11,12', 'Black|#000000,Brown|#8B4513,Burgundy|#800020', '2025-10-29 07:54:57'),
(108, 'Wedge Sandals', 'Footwear', 5500.00, 'Comfortable wedge sandals with height', 'https://tse2.mm.bing.net/th/id/OIP.f1qf-Tny0WfovAUuI4Ch_wHaJE?cb=ucfimg2ucfimg=1&rs=1&pid=ImgDetMain&o=7&rm=3', 4.60, '6,7,8,9,10,11', 'Black|#000000,Navy|#001f3f', '2025-10-29 07:54:57'),
(109, 'Mules', 'Footwear', 4800.00, 'Trendy mules for easy wear', 'https://th.bing.com/th/id/R.aa426d2bc2890882d79b94f617c83a5b?rik=M89ubGsiFQRfsQ&riu=http%3a%2f%2fwww.thebudgetfashionista.com%2fwp-content%2fuploads%2f2016%2f07%2fbrown-mule-shoes-.jpg&ehk=%2fuCLiqKf%2fBSrTJOd0FHMQbYqj75wmGAJEdpup4OvZ2c%3d&risl=&pid=ImgRa', 4.50, '6,7,8,9,10,11', 'Black|#000000,White|#FFFFFF,Red|#FF0000', '2025-10-29 07:54:57'),
(110, 'Chelsea Boots', 'Footwear', 8200.00, 'Classic Chelsea boots with elastic sides', 'https://images.unsplash.com/photo-1608256246200-53e635b5b65f?w=500', 4.70, '6,7,8,9', 'Black|#000000,Brown|#8B4513', '2025-10-29 07:54:57'),
(111, 'Boat Shoes', 'Footwear', 5800.00, 'Casual boat shoes for nautical style', 'https://tse2.mm.bing.net/th/id/OIP.E_sk2tJl4fpj0mbotCjIfwHaHa?cb=ucfimg2ucfimg=1&rs=1&pid=ImgDetMain&o=7&rm=3', 4.60, '6,7,8,9,10,11,12', 'Brown|#8B4513,Navy|#001f3f', '2025-10-29 07:54:57'),
(112, 'Platform Shoes', 'Footwear', 7200.00, 'Trendy platform shoes for added height', 'https://th.bing.com/th/id/OIP.ylFhL76rK1VQ3kthNhHuKQHaHa?w=205&h=205&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3', 4.60, '6,7,8,9,10,11', 'Black|#000000,White|#FFFFFF,Pink|#FF69B4', '2025-10-29 07:54:57'),
(113, 'Slippers', 'Footwear', 2200.00, 'Cozy slippers for indoor comfort', 'https://th.bing.com/th/id/OIP.OgnQ2d8L1vGrQMgvYizb1QHaHa?w=183&h=183&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3', 4.40, 'S,M,L,XL', 'Gray|#808080,Black|#000000,Pink|#FF69B4', '2025-10-29 07:54:57'),
(114, 'Oxfords', 'Footwear', 7800.00, 'Classic oxford shoes for formal wear', 'https://dhb3yazwboecu.cloudfront.net/909/black_calf_oxford_shoes_carmina_732_l.jpg', 4.70, '6,7,8,9,10,11,12', 'Black|#000000,Brown|#8B4513', '2025-10-29 07:54:57'),
(115, 'Rain Boots', 'Footwear', 4500.00, 'Waterproof boots for rainy weather', 'https://th.bing.com/th/id/OIP.5ZZik6Jq4pLGrrsOKSENxQHaGe?w=211&h=184&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3', 4.50, '6,7,8,9,10,11,12', 'Black|#000000,Red|#FF0000,Yellow|#FFFF00', '2025-10-29 07:54:57'),
(116, 'Mary Janes', 'Footwear', 4200.00, 'Classic Mary Jane style shoes', 'https://fairymadeshop.com/wp-content/uploads/2018/11/Mary-Jane-Black-Patent-Heels-1.jpg', 4.50, '6,7,8,9', 'Yellow|#FFFF00,Beige|#F5F5DC,Orange|#FF8C00,Maroon|#800000', '2025-10-29 07:54:57'),
(117, 'Men\'s Slim Fit Formal Trouser Black', '', 4000.00, 'Tailored for the modern man who values style, comfort, and confidence', 'https://cdn.greencloudpos.com/nolimit.lk/product/NOLIMITMen\'sSlimFitFormalTrouserBlack-0-1757304762774-NLMGentsFormalTrouser-0-1708316601834-NLMGentsFormalTrouserBlack%C3%83%C2%A2_%C3%82%C2%A228-1674194505937.webp', 4.50, 'S,L,XL', 'Black|#000000,Maroon|#800000', '2025-11-12 07:34:08'),
(118, 'Men\'s Formal Regular Fit Shirt', '', 2490.00, 'Formal Shirt for Important Occasions', 'https://cdn.greencloudpos.com/nolimit.lk/product/NOLIMITMen\'sFormalRegularFitShirt-0-1721725571110-DSC08967.jpg', 4.50, 'S,M,L', 'White|#FFFFFF', '2025-11-12 07:43:13'),
(119, 'Side Bag Girls', '', 1290.00, 'Stay stylish and organized on-the-go with our trendy side bags for girls.', 'https://www.nolimit.lk/_next/image?url=https%3A%2F%2Fcdn.greencloudpos.com%2Fnolimit.lk%2Fproduct%2FSideBagGirls-1-1755579885251-12_0023_0Y7A4668.png%3Fwidth%3D600&w=1080&q=75', 4.50, 'One Size', 'Red|#FF0000,Beige|#F5F5DC,Cream|#FFFDD0', '2025-11-12 12:05:22'),
(120, 'School bag Nursery Back pack', '', 2500.00, 'The padded shoulder straps and back panel offer superior comfort, making it easy for your child to carry their bag around, whether they\'re heading to nursery, school, or a fun-filled day outdoors.', 'https://cdn.greencloudpos.com/nolimit.lk/product/SchoolBagNurseryBackPack-1-1755579139110-1_0001_0Y7A4654.png', 4.50, 'One Size', 'Gray|#808080,Navy|#001f3f,Orange|#FF8C00', '2025-11-12 12:06:49'),
(121, 'Girl\'s Digital Water Resistant Sports Watch', '', 1500.00, 'This girls\' watch has a 45mm dial width, 13mm thickness, and a PMMA lens for durability', 'https://cdn.greencloudpos.com/nolimit.lk/product/Girl\'sDigitalWaterResistantSportsWatch-0-1735032185437-5.jpg', 4.50, 'One Size', 'Gray|#808080,Brown|#8B4513,Navy|#001f3f', '2025-11-12 12:09:31'),
(122, 'School Back Pack', '', 2000.00, 'The padded shoulder straps and back panel offer superior comfort, making it easy for your child to carry their bag around', 'https://cdn.greencloudpos.com/nolimit.lk/product/SchoolBagNurseryBackPack-3-1755580748556-12_0010_0Y7A4685.png', 4.50, 'One Size', 'Blue|#0074D9,Gray|#808080,Navy|#001f3f', '2025-11-12 15:06:21'),
(123, 'Tie clip', '', 600.00, 'Tie Clip Necktie Accessories Fashion Style Ties for Men Metal Tone Simple Bar Clasp Practical Clasp Tie Pin for Mens Collar Clip', 'https://img.drz.lazcdn.com/g/kf/S2ec57d071ee64372a3b7a97012a110d9o.jpg_720x720q80.jpg_.webp', 4.50, 'One Size', 'Green|#2ECC40,Gray|#808080,Navy|#001f3f', '2025-11-12 15:09:23'),
(124, 'Multi Function Compass Belt', '', 1000.00, 'Men\'s Belt Outdoor Multi Function Compass Belt High Quality Canvas For Nylon Male Luxury Belts Women\'s Sports Jeans Neutral Belt', 'https://img.drz.lazcdn.com/g/kf/S2ccd5dcbc4274c94abfacb4e24347006a.jpg_720x720q80.jpg_.webp', 4.50, 'One Size', 'Red|#FF0000,Blue|#0074D9,Beige|#F5F5DC', '2025-11-12 15:11:25'),
(125, 'Cotton Mix Plain Bucket Cap', '', 1500.00, 'Very comfortable for your head.meterial = cotton / polyester .', 'https://img.drz.lazcdn.com/g/kf/Sa01a48db730649e291ecd1239cdb8672X.jpg_720x720q80.jpg_.webp', 4.50, 'One Size', 'Black|#000000,Navy|#001f3f,Maroon|#800000', '2025-11-12 15:13:58'),
(126, 'Sunshade Fisherman Hat', '', 2000.00, 'Sunshade Fisherman Hat for outdoors', 'https://img.drz.lazcdn.com/g/kf/S26e136fa879842ca9f5ffe0619b21961D.jpg_720x720q80.jpg_.webp', 4.50, 'One Size', 'Black|#000000,Red|#FF0000,Brown|#8B4513', '2025-11-12 15:15:30'),
(127, 'FERRARI Branded Flip Flops ', '', 2000.00, 'High Quality Rubber Material,Genuine Rubber Strapped Brand Label Pasted. Soft and Comfortable', 'https://img.drz.lazcdn.com/static/lk/p/76258f265b17994fa5c7dc6ddbdca3f2.jpg_720x720q80.jpg_.webp', 4.50, '6,9,10', 'Green|#2ECC40,Gray|#808080,Brown|#8B4513', '2025-11-12 15:17:15'),
(128, 'Business Casual Long Pants', '', 2500.00, 'Men\'s Spring Autumn New Fashion Business Casual Long Pants Suit Pants Male Elastic Straight Formal Trousers', 'https://img.drz.lazcdn.com/g/kf/Scc45a5f78b914b4696efbdcb6c63214cT.jpg_720x720q80.jpg_.webp', 4.50, 'M,XL,XXL', 'Black|#000000,Navy|#001f3f,Maroon|#800000', '2025-11-12 15:18:41'),
(129, 'Long Sleeve White Shirt', '', 2500.00, 'Plain White Formal Cotton Shirt', 'https://img.drz.lazcdn.com/static/lk/p/e5c11591a97361382461ac05d79ba094.png_720x720q80.png_.webp', 4.50, 'L,XXL,2XL', 'White|#FFFFFF', '2025-11-12 15:20:19'),
(130, 'Premium Linen Short Sleeve Shirt', '', 3000.00, 'MEEZ Premium Linen Short Sleeve Shirts', 'https://img.drz.lazcdn.com/static/lk/p/fd71082bcd01977d7a61be08bd25e584.jpg_720x720q80.jpg_.webp', 4.50, 'M,L,XL', 'Black|#000000,Green|#2ECC40,Navy|#001f3f,Orange|#FF8C00', '2025-11-12 15:21:59'),
(131, 'Men’s Slim Fit Chino Pant', '', 4000.00, 'These slim-fit chino pants are designed for men who appreciate style and comfort.', 'https://img.drz.lazcdn.com/g/kf/S782807f7815947adb0397263b442429cS.jpg_720x720q80.jpg_.webp', 4.50, 'S,L,XL', 'Black|#000000,Red|#FF0000,Navy|#001f3f', '2025-11-12 15:24:59'),
(132, 'Long Sleeves Applique A Line Chiffon Maxi Skirt', '', 6000.00, 'BABYONLINE Boho Wedding Dresses Chic and Simple V Neck Long Sleeves Applique A Line Chiffon Maxi Skirt', 'https://img.drz.lazcdn.com/g/kf/Sf6af12d400874f9bb287027f03479a4e0.jpg_720x720q80.jpg_.webp', 4.50, 'S,M,XL', 'White|#FFFFFF,Blue|#0074D9,Beige|#F5F5DC', '2025-11-12 15:27:54'),
(133, 'Chinese Style Hairpin', '', 700.00, 'Neo Chinese Style Hairpin Light Luxury Metal Rose Moon Butterfly Element Hair Stick For Women Hanfu Horse-face Skirt Hairpin', 'https://img.drz.lazcdn.com/g/kf/S0e0e0fc204414a53ae0c8aa7df063032M.jpg_720x720q80.jpg_.webp', 4.20, 'One Size', 'Black|#000000,Red|#FF0000,Navy|#001f3f', '2025-11-12 15:29:36'),
(134, 'Big Hair Claw Clips', '', 600.00, 'Big Hair Claw Clips for Women Girls Thin Thick Curly Hair, Strong Hold Jaw Clips.', 'https://img.drz.lazcdn.com/g/kf/S11bad03d6ec843038338274ffd36b001A.jpg_720x720q80.jpg_.webp', 4.50, 'One Size', 'Black|#000000,Brown|#8B4513,Navy|#001f3f', '2025-11-12 15:31:03'),
(135, 'Metal Hair Band', '', 500.00, 'Metal materials will not easy to deform,durable and weaproof,high intensity and longlife', 'https://img.drz.lazcdn.com/static/lk/p/4af7904fe080691baec2a4950efbf497.jpg_720x720q80.jpg_.webp', 4.50, 'One Size', 'Black|#000000,Brown|#8B4513,Maroon|#800000', '2025-11-12 15:38:02'),
(136, 'Fabric Covered Resin Hairbands', '', 500.00, 'Plain Solid 5MM Satin Fabric Covered Resin Hairbands Stainless Steel Headband Hair Bands Accessories', 'https://img.drz.lazcdn.com/static/lk/p/20ad523808e2373da7205c994f089a7c.jpg_720x720q80.jpg_.webp', 4.50, 'One Size', 'Blue|#0074D9,Purple|#9333ea,Gray|#808080', '2025-11-12 15:41:00'),
(137, 'Warm Knitted Hat', '', 900.00, 'Autumn Winter Baby Warm Knitted Hats With Pom Kids Knit Beanie Hat Solid Color Children Hat For Boys Girls Accessories', 'https://img.drz.lazcdn.com/g/kf/Sa05db6f3842a4422ba0ec0c6d13c230eo.jpg_720x720q80.jpg_.webp', 4.50, 'One Size', 'White|#FFFFFF,Beige|#F5F5DC,Olive|#808000', '2025-11-12 15:42:15'),
(138, 'Insulated Lunch Bag', '', 1000.00, 'Waterproof Thickened Aluminum Foil Kids Small Portable Lunch Box', 'https://img.drz.lazcdn.com/static/lk/p/7e97f55753a72f67fb89fd9f965ee576.png_720x720q80.png_.webp', 4.50, 'One Size', 'Black|#000000,Gray|#808080,Brown|#8B4513', '2025-11-12 15:43:54'),
(139, 'Elementary School Baby Umbrella', '', 950.00, 'Waterproof Cover Boys and Girls Elementary School Baby Umbrella', 'https://img.drz.lazcdn.com/static/lk/p/ae66fc3a208e4cb9a08520577ff5486f.jpg_720x720q80.jpg_.webp', 4.50, 'One Size', 'Green|#2ECC40,Gray|#808080,Cream|#FFFDD0', '2025-11-12 15:45:57'),
(140, 'Salwari', '', 4500.00, 'THis is new', 'https://tse1.mm.bing.net/th/id/OIP.vZG1e8B_Jef6RgW-6KZUOwHaKx?cb=ucfimg2&ucfimg=1&rs=1&pid=ImgDetMain&o=7&rm=3', 5.00, 'S,M,XL', 'Black|#000000,Green|#2ECC40,Navy|#001f3f', '2025-12-11 06:23:08'),
(141, 'Long Sleeve T Shirt', '', 2000.00, 'T-Shirt Republic - Raven Black Men\'s Premium Long Sleeve T Shirt', 'https://img.drz.lazcdn.com/static/lk/p/1011f2e1cd3377e8e12b095abd86049f.jpg_720x720q80.jpg_.webp', 4.50, 'S,M,L,XL', 'Black|#000000,Red|#FF0000,Navy|#001f3f', '2026-01-25 04:31:37'),
(142, 'Classic Oxford Formal Shirt', '', 3500.00, 'Long‑sleeve cotton oxford shirt, perfect for office or events, breathable and easy‑iron fabric', 'https://th.bing.com/th/id/OIP.GFsQnrwQ4PQh7293eZpMAQAAAA?w=187&h=216&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3', 4.50, 'M,L,XL', 'White|#FFFFFF,Blue|#0074D9', '2026-02-01 07:48:52'),
(143, 'Slim Fit Stretch Chinos', '', 4200.00, 'Slim fit chinos with a bit of stretch for daily comfort, suitable for office or casual wear.', 'https://tse4.mm.bing.net/th/id/OIP.wrA8F_67jHsarFd4WuJ-3AHaJC?rs=1&pid=ImgDetMain&o=7&rm=3', 4.50, 'L,XL,XXL', 'Navy|#001f3f,Khaki|#C3B091', '2026-02-01 07:53:11'),
(144, 'Casual Cotton Henley T‑Shirt', '', 2500.00, 'Soft cotton Henley with three‑button placket, ideal for relaxed weekends and layering.', 'https://th.bing.com/th/id/OIP.luXzeonvFSTskYUF2E6oHwHaHa?w=199&h=199&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3', 4.50, 'M,L,XL', 'Green|#2ECC40,Beige|#F5F5DC', '2026-02-01 07:55:16'),
(145, 'Lightweight Zip Hoodie', '', 3800.00, 'Lightweight zip‑up hoodie with front pockets, good for travel, gym, or evening walks.', 'https://th.bing.com/th/id/OIP.t9aW_weslS1SwUTxedywMQHaIS?w=187&h=209&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3', 4.50, 'M,L,XL,XXL', 'Black|#000000,Brown|#8B4513,Navy|#001f3f', '2026-02-01 07:56:23'),
(146, 'Belted Trench Coat', '', 5500.00, 'Classic mid‑length trench coat with waist belt, double‑breasted front, and water‑repellent fabric for rainy or windy days.', 'https://th.bing.com/th/id/OIP.2_M_KO2kDJL7Vwr2OWEQ1QHaLW?w=187&h=288&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3', 4.50, 'S,M,L', 'Beige|#F5F5DC,Khaki|#C3B091', '2026-02-01 07:58:40'),
(147, 'Quilted Bomber Jacket', '', 5200.00, 'Lightweight quilted bomber jacket with ribbed cuffs and hem, ideal as everyday outerwear for mild to cool weather.', 'https://th.bing.com/th/id/OIP.QGM0Mpz8jwujluidUXsfhAHaJD?w=187&h=228&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3', 4.70, 'M,L,XL,XXL', 'Black|#000000,Green|#2ECC40', '2026-02-01 07:59:46');

-- --------------------------------------------------------

--
-- Table structure for table `product_categories`
--

CREATE TABLE `product_categories` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_categories`
--

INSERT INTO `product_categories` (`id`, `product_id`, `category_id`) VALUES
(1, 1, 1),
(2, 1, 6),
(97, 2, 2),
(98, 2, 5),
(340, 3, 1),
(341, 3, 5),
(203, 4, 1),
(204, 4, 6),
(329, 5, 1),
(330, 5, 6),
(342, 6, 3),
(167, 7, 4),
(168, 7, 5),
(55, 8, 1),
(56, 8, 5),
(498, 9, 2),
(499, 9, 5),
(69, 10, 1),
(70, 10, 5),
(338, 11, 1),
(339, 11, 5),
(57, 12, 1),
(58, 12, 5),
(101, 13, 2),
(102, 13, 5),
(81, 14, 1),
(82, 14, 5),
(365, 15, 3),
(366, 15, 5),
(169, 16, 4),
(170, 16, 5),
(3, 17, 1),
(4, 17, 6),
(327, 18, 2),
(328, 18, 6),
(325, 19, 2),
(326, 19, 6),
(323, 20, 1),
(324, 20, 6),
(514, 21, 2),
(515, 21, 6),
(319, 22, 1),
(320, 22, 6),
(317, 23, 1),
(318, 23, 6),
(315, 24, 1),
(316, 24, 6),
(313, 25, 1),
(314, 25, 6),
(311, 26, 1),
(312, 26, 6),
(438, 27, 2),
(439, 27, 6),
(309, 28, 2),
(310, 28, 6),
(47, 29, 2),
(48, 29, 6),
(307, 30, 1),
(308, 30, 6),
(305, 31, 1),
(306, 31, 6),
(303, 32, 2),
(304, 32, 6),
(21, 33, 1),
(22, 33, 6),
(31, 34, 1),
(32, 34, 6),
(408, 35, 1),
(409, 35, 6),
(301, 36, 1),
(302, 36, 6),
(528, 37, 1),
(529, 37, 5),
(71, 38, 1),
(72, 38, 5),
(335, 39, 1),
(336, 39, 5),
(85, 40, 1),
(86, 40, 5),
(359, 41, 1),
(360, 41, 5),
(91, 42, 1),
(92, 42, 5),
(299, 43, 1),
(300, 43, 6),
(333, 44, 1),
(334, 44, 5),
(61, 45, 1),
(62, 45, 5),
(331, 46, 1),
(332, 46, 5),
(297, 47, 2),
(298, 47, 6),
(347, 48, 1),
(348, 48, 6),
(349, 49, 1),
(350, 49, 6),
(343, 50, 1),
(344, 50, 6),
(295, 51, 1),
(296, 51, 6),
(424, 52, 1),
(425, 52, 5),
(440, 53, 1),
(441, 53, 5),
(67, 54, 1),
(68, 54, 5),
(287, 55, 1),
(288, 55, 6),
(285, 56, 1),
(286, 56, 5),
(283, 57, 1),
(284, 57, 7),
(414, 58, 1),
(415, 58, 7),
(279, 59, 1),
(280, 59, 7),
(275, 61, 1),
(276, 61, 7),
(273, 62, 2),
(274, 62, 7),
(412, 63, 1),
(413, 63, 7),
(269, 64, 1),
(270, 64, 7),
(267, 65, 1),
(268, 65, 7),
(263, 66, 1),
(264, 66, 7),
(261, 67, 1),
(262, 67, 7),
(496, 68, 1),
(497, 68, 7),
(420, 69, 1),
(421, 69, 7),
(504, 70, 1),
(505, 70, 7),
(416, 71, 1),
(417, 71, 7),
(494, 72, 2),
(495, 72, 7),
(143, 73, 3),
(144, 73, 7),
(249, 74, 1),
(250, 74, 7),
(247, 75, 1),
(248, 75, 7),
(418, 76, 2),
(419, 76, 7),
(147, 77, 3),
(363, 78, 3),
(364, 78, 6),
(149, 79, 3),
(378, 80, 3),
(379, 81, 3),
(380, 81, 6),
(152, 82, 3),
(153, 83, 3),
(154, 84, 3),
(369, 85, 3),
(370, 85, 5),
(383, 86, 3),
(384, 86, 5),
(436, 87, 3),
(437, 87, 6),
(241, 88, 3),
(361, 89, 3),
(362, 89, 6),
(160, 90, 3),
(357, 91, 3),
(358, 91, 5),
(162, 92, 3),
(163, 93, 3),
(444, 94, 3),
(445, 94, 6),
(351, 95, 3),
(352, 95, 5),
(353, 96, 3),
(354, 96, 6),
(236, 97, 4),
(237, 97, 6),
(235, 98, 4),
(233, 99, 4),
(234, 99, 5),
(183, 100, 4),
(184, 100, 6),
(232, 101, 4),
(401, 102, 4),
(402, 103, 4),
(403, 103, 5),
(230, 104, 4),
(231, 104, 6),
(228, 105, 4),
(229, 105, 5),
(226, 106, 4),
(227, 106, 6),
(175, 107, 4),
(176, 107, 5),
(446, 108, 4),
(447, 108, 6),
(442, 109, 4),
(443, 109, 6),
(398, 110, 4),
(430, 111, 4),
(431, 111, 5),
(220, 112, 4),
(221, 112, 6),
(207, 113, 4),
(434, 114, 4),
(435, 114, 5),
(210, 115, 4),
(211, 115, 6),
(428, 116, 4),
(429, 116, 6),
(404, 117, 2),
(405, 117, 5),
(406, 118, 2),
(407, 118, 5),
(448, 119, 3),
(449, 119, 7),
(450, 120, 3),
(451, 120, 7),
(452, 121, 3),
(453, 121, 7),
(458, 122, 3),
(459, 122, 7),
(456, 123, 3),
(457, 123, 5),
(460, 124, 3),
(461, 124, 5),
(462, 125, 3),
(463, 125, 5),
(510, 126, 3),
(511, 126, 5),
(466, 127, 4),
(467, 127, 5),
(468, 128, 2),
(469, 128, 5),
(470, 129, 2),
(471, 129, 5),
(472, 130, 2),
(473, 130, 5),
(474, 131, 2),
(475, 131, 5),
(476, 132, 2),
(477, 132, 6),
(478, 133, 3),
(479, 133, 6),
(480, 134, 3),
(481, 134, 6),
(482, 135, 3),
(483, 135, 6),
(484, 136, 3),
(485, 136, 7),
(486, 137, 3),
(487, 137, 7),
(488, 138, 3),
(489, 138, 7),
(490, 139, 3),
(491, 139, 7),
(502, 140, 1),
(503, 140, 6),
(508, 141, 2),
(509, 141, 5),
(516, 142, 2),
(517, 142, 5),
(518, 143, 1),
(519, 143, 5),
(520, 144, 1),
(521, 144, 5),
(522, 145, 1),
(523, 145, 5),
(524, 146, 1),
(525, 146, 6),
(530, 147, 1),
(531, 147, 5);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `email`, `password`, `address`, `city`, `created_at`) VALUES
(1, 'Kaveesha Amiru', 'kaveeshaamiru05@gmail.com', '$2y$10$ELYVGZ8oqq/3Sev1MO7Fgubm15JkiwZGU.zlXqbQayXqEEN.u8N8K', '58,Pahala Kadirana, Thimbirigaskatuwa', 'Negombo', '2025-11-12 08:40:31'),
(2, 'Kumara Fernando', 'Kumara1@gmail.com', '$2y$10$AHv8rpjOX8oQ1s6lJC0lBOM4c66.XaQx7.pB//u6EvcnmlBFWH/bC', '58,Flower Road', 'Negombo', '2025-11-12 08:41:31'),
(3, 'Sanka Silva', 'sanka123@gmail.com', '$2y$10$54mg5X5RVhBTLdQd9wHeqOsorg/0A40ik1rPrFmx6oV75k1Dq0XBy', '123, Flower Road', 'Gampaha', '2026-01-25 03:17:36');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `inquiries`
--
ALTER TABLE `inquiries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_created_at` (`created_at`);

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
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_categories`
--
ALTER TABLE `product_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_product_category` (`product_id`,`category_id`),
  ADD KEY `category_id` (`category_id`);

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
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `inquiries`
--
ALTER TABLE `inquiries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=148;

--
-- AUTO_INCREMENT for table `product_categories`
--
ALTER TABLE `product_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=532;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

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
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `product_categories`
--
ALTER TABLE `product_categories`
  ADD CONSTRAINT `product_categories_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_categories_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
